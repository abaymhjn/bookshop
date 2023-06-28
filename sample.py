# -*- coding: utf-8 -*-
import json
from datetime import datetime
from random import randint, uniform

# Sample data for generating records
sample_data = [
    {
        "sale_id": "1",
        "customer_name": "Reto Fanzen",
        "customer_mail": "reto.fanzen@no-reply.rexx-systems.com",
        "product_id": 1,
        "product_name": "Refactoring: Improving the Design of Existing Code",
        "product_price": "49.99",
        "sale_date": "2019-04-02 08:05:12"
    },
    {
        "sale_id": "2",
        "customer_name": "Reto Fanzen",
        "customer_mail": "reto.fanzen@no-reply.rexx-systems.com",
        "product_id": 2,
        "product_name": "Clean Architecture: A Craftsman's Guide to Software Structure and Design",
        "product_price": "24.99",
        "sale_date": "2019-05-01 11:07:18"
    },
    {
        "sale_id": "3",
        "customer_name": "Leandro Bußmann",
        "customer_mail": "leandro.bussmann@no-reply.rexx-systems.com",
        "product_id": 2,
        "product_name": "Clean Architecture: A Craftsman's Guide to Software Structure and Design",
        "product_price": "19.99",
        "sale_date": "2019-05-06 14:26:14"
    },
    {
        "sale_id": "4",
        "customer_name": "Hans Schäfer",
        "customer_mail": "hans.schaefer@no-reply.rexx-systems.com",
        "product_id": 1,
        "product_name": "Refactoring: Improving the Design of Existing Code",
        "product_price": "37.98",
        "sale_date": "2019-06-07 11:38:39"
    },
    {
        "sale_id": "5",
        "customer_name": "Mia Wyss",
        "customer_mail": "mia.wyss@no-reply.rexx-systems.com",
        "product_id": 1,
        "product_name": "Refactoring: Improving the Design of Existing Code",
        "product_price": "37.98",
        "sale_date": "2019-07-01 15:01:13"
    },
    {
        "sale_id": "6",
        "customer_name": "Mia Wyss",
        "customer_mail": "mia.wyss@no-reply.rexx-systems.com",
        "product_id": 2,
        "product_name": "Clean Architecture: A Craftsman's Guide to Software Structure and Design",
        "product_price": "19.99",
        "sale_date": "2019-08-07 19:08:56"
    }
]

# Generate 10 million records based on the sample data
total_records = 100
batch_size = 100  # Number of records to generate per batch

filename = "large_sample.json"  # Output file to store all generated records

with open(filename, "w") as outfile:
    outfile.write("[")  # Write the opening square bracket

    for i in range(total_records):
        sample = sample_data[i % len(sample_data)].copy()
        sample["sale_id"] = str(i + 1)
        sample["product_id"] = randint(1, 2)
        sample["product_price"] = "{:.2f}".format(uniform(10.0, 100.0))
        sample["sale_date"] = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        
        json.dump(sample, outfile)
        
        if i < total_records - 1:
            outfile.write(",\n")  # Add a comma and newline for all records except the last one

    outfile.write("]")  # Write the closing square bracket

print("Large sample JSON generated with", total_records, "records.")