# bookshop

require halaxa/json-machine Used for large json file reading

# instructions

Run composer install to install required json-machine which is useful to read very large file.

index.php is used to view saved data in datatable using serverside ajax pagination to prevent memory issue to load data at once.

sample.py is phython script I have used to generate 10 million record json so I can test large save operation.

execute phython sample.py to generate json file with required number of records.

read_and_save_json_data.php is used to upload json file using plupload so we can upload large file easily e.g. I have uploaded file of 10 million records using sample json file.

# Not done

Upload file data structure valition is not done.

Resumable read and save functionality is not done. 

I have planned to do that, but I did not find solution for this issue.


