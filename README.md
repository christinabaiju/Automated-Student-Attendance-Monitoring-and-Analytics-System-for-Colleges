Problem Statement: Automated Student Attendance Monitoring and Analytics System for Colleges.
PS Number: SIH25012

Importance of this project:
1) Maximises instructional time by automating attendance, freeing faculty from tedious manual processes.
2) Eliminates attendance fraud and human error, ensuring trustworthy and precise attendance data.
3) Empowers educators with insights to identify and support students who may be at academic risk.
4) Boosts transparency and accountability among students, faculty, and parents through real-time attendance visibility.
5) Accelerates institutional digital transformation, aligning colleges with modern, efficient educational practices.

## Table of Contents
- [Introduction](#introduction)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Contribution](#contribution)
- [References](#References)

## Introduction
This prototype project allows the users who are students or admins to perform attendance-related tasks efficiently while reducing
the valuable time wasted during Manual attendance taking. This project not only allows the students present in class to mark their 
attendance but also allows the admin/ faculty with the proper credentials to access the attendance dashboard, which helps directly print the attendance through a CSV file, instead of manually feeding the attendance into Excel after taking attendance. 

This process can be done once the student reaches the college and gets seated in the class by embedding this prototype in the professor's device while the student has the QR code available either on their IDS or on their phone. This is a scalable project, meaning it can be extended to fit attendance taking in a subject-based format in every class in the future. 

Programming languages:
1) HTML
2) CSS
3) JavaScript
4) PHP
5) SQL

Technology Used:
1) XAMPP Server
2) PhpMyAdmin(MySQL)
3) QR Tiger

## Features
- QR Code Scanner to move to the Attendance Marking Page.
- Admin-only access to the Attendance Analytics Dashboard.
- Visually impactful pie chart to Showcase Attendance for every day (changing).
-Easily accessible CSV file to download the attendance.

## Installation
We need to install XAMPP server for the working of this project:

Go to the website link https://www.apachefriends.org/index.html to download the XAMPP Server on your computer. Follow the steps prompted to correctly install the application.


## Usage

1.	Scan the QR code on the QR code Scanner. (QR_HTMLJS.php)
2.	This will redirect the Student to the Attendance Page, where they can mark their attendance using the student role. (Login_page.php)
These records get stored in the "users" table of "attendance_system" database.
3.	The Admin can gain access to the dashboard by entering their actual credentials ie; (official@admin, 1432), using the admin role.
(Attendance_Admin_Dashboard.php)
4.	Once they have gained access to the dashboard, they can see the visual analytics and download the attendance for that day.
These records are retrieved by joining the "attendance" and "users" tables.

## Contribution
1.	Christina - QR_HTMLJS.php, Login_page.php, Attendance_Admin_Dashboard.php, Database (attendance_system)
2.	Arushi - Login_page.php,  Login_CSS.css, Attendance_Admin_Dashboard.php
3.	Shambhavi - Database (attendance_system)
4.	Deepanshi - Login_page.php (Data entry - scenarios)
5.	Mehak - Attendance_Admin_Dashboard.php
6.	Rishabh - Presentation

## References
1.	ChatGPT
2.	Perplexity
3.	YouTube 
