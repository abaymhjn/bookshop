<!DOCTYPE html>
<html>
<head>
    <title>Import Sales Data from Json</title>
    <link id="style" href="js/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="js/sweet-alert/sweetalert.css">
    <link href="js/fileupload/css/fileupload.css" rel="stylesheet" type="text/css" />
    <style>
        .row {
            margin : 1%;
        }
        .up_div {
            position: absolute;
            z-index: 1;
            padding: 5px;
            width: 100%;
            height: 100%;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            overflow: hidden;
        }
    </style>    
</head>
<body>
    <div class="row">
        <div class="col-12">
            <h1>Import Data from Json File</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="dropify-wrapper" id="json" name="json" style="height: 192px;">
                <div class="dropify-message">
                    <span class="file-icon"> <p>Drag and drop a file here or click</p></span>
                    <p class="dropify-error" id="json_error">Ooops, something wrong happended.</p>
                </div>
                <div class="dropify-loader" style="display: none;"></div>
                <div class="dropify-errors-container"><ul></ul></div>
                <button type="button" class="dropify-clear" id="json_delete">Remove</button>
                <div id="json_up" name="json_up" class="up_div">
                    <div class="dropify-preview" id="json_preview_div" style="display:none;">
                        <span class="dropify-render" id="json_preview">
                        </span>
                        <div class="dropify-infos">
                            <div class="dropify-infos-inner">
                                <p class="dropify-filename"><span class="dropify-filename-inner" id="json_file_name">
                                </span></p>
                                <p class="dropify-infos-message">Drag and drop or click to replace</p>
                            </div>    
                        </div>
                    </div>
                </div>    
            </div>
        </div>
        <div class="col-3">
            <a href="javascript:save_data_in_database();" class="btn btn-info" id="process_data" style="display:none;">Import Data from Json File</a>
            <input type="hidden" id="nfile_path" name="nfile_path" value="">
        </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.blockUI.js"></script>    
    <script src="js/bootstrap/popper.min.js"></script>
    <script src="js/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/sweet-alert/sweetalert.min.js"></script>
    <script src="js/plupload/3.1.5/plupload.full.min.js"></script>
    <script>
        var json_list = document.getElementById("json_preview");

        var json_uploader = new plupload.Uploader({
            runtimes: "html5",
            multi_selection: false,
            browse_button: "json_up",
            url: "_ajax_upload_json_in_chunk.php",
            chunk_size: "10mb",
            unique_names : true,
            dragdrop: true,
            drop_element : 'json_up',
            filters : {
                max_file_size : '10gb',
                prevent_empty: true,
                mime_types : [
                    {extensions : "json"},
                ]
            },
            init: {
                FilesAdded: (up, files) => {
                    $('#json_preview_div').show();
                    $('#json').removeClass("has-error");
                    plupload.each(files, file => {
                        json_list.innerHTML = `<i class="dropify-font-file"></i><span class="dropify-extension"></span>`;
                    });
                    json_uploader.start();
                },
                UploadProgress: (up, file) => {
                    document.querySelector(`#json_preview span`).innerHTML = `${file.percent}%`;
                },
                FileUploaded: function(up, file, info) {
                    var response = JSON.parse(info.response);
                    console.log(response);
                    if(response.OK==1)
                    {
                        $('#json').addClass("has-preview");
                        $("#json_preview").html('<i class="dropify-font-file"></i><span class="dropify-extension">json</span>');
                        $('#nfile_path').val(response.name);
                        $('#json').data('default-file', 'something');
                        $('#json_preview_div').show();
                        $('#process_data').show();
                    } else {
                        $('#process_data').hide();
                        $('#nfile_path').val('');
                    }
                },
                Error: function (up, err){ 
                    console.log(err);
                    $('#json').removeClass("has-preview");
                    $('#json').addClass("has-error");
                    $('#json_preview_div').hide();
                    $("#json_error").html('File type not allowed');
                    $('#process_data').hide();
                    $('#nfile_path').val('');
                } 
            }
        });
        json_uploader.init();

        function save_data_in_database() {
            var file_name = $('#nfile_path').val();
            if(file_name !='') {
                swal({
                    title: "Are you sure?",
                    text: "Are you sure to Save file data to database?",
                    icon: "warning",
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    dangerMode: true,
                }, function () {
                    $.blockUI();
                    $.ajax({
                        url: "_ajax_read_and_save_json_data.php",
                        type: "POST",
                        data:{
                            file_name : file_name
                        },
                        dataType:'json',
                        success: function (data) {
                            $.unblockUI();
                            if(data.status==true)
                            {
                                swal("Success!", data.message, "success");
                                window.location.reload();
                            }
                            else
                            {
                                swal("Error!", data.message, "error");
                                window.location.reload();
                            }
                        },
                        error: function (err) {
                            $.unblockUI();
                            swal("Error!", err.message, "error");
                            window.location.reload();
                        }
                    });
                });
            } else {
                swal("Error!", 'File is not uploaded. Please upload file again', "error");
                window.location.reload();
            }
        }
    </script>
</body>
</html>
