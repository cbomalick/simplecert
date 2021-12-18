<?php

//Import classes

/* System Utilities */
require_once('database.class.php');
require_once('table.class.php');
require_once('site.class.php');
require_once('alert.class.php');
require_once('lov.class.php');
require_once('auditlog.class.php');
require_once('idnumber.class.php');
require_once('timehandler.class.php');

/* Company */
require_once('company.class.php');

/* Communication (Email, Phone, Fax) */
require_once('email.class.php');
require_once('phone.class.php');
require_once('address.class.php');

/* Person (Prospect, Customer, Employee) */
require_once('person.class.php');
require_once('personlist.class.php');
require_once('customer.class.php');
require_once('customerlist.class.php');

// /* Record (Services, Events, Repairs, Special Services) */
require_once('service.class.php');
require_once('servicelist.class.php');
require_once('event.class.php');
require_once('eventlist.class.php');

// /* Record Add-ons (Metrics, Notes) */
require_once('metric.class.php');
require_once('metriclist.class.php');
require_once('note.class.php');
require_once('notelist.class.php');
require_once('material.class.php');
require_once('materiallist.class.php');

/* User */
require_once('user.class.php');
require_once('timehandler.class.php');
require_once('session.class.php');

/* Dashboard */
require_once('dashboard.class.php');

// /* Attachments (Photo, document, etc) */
require_once('attachment.class.php');
require_once('image.class.php');
require_once('imagelist.class.php');
// //require_once('pdfwriter.class.php');

// /* Location (Pool, spa, playground, pavillion, etc) */
require_once('location.class.php');
require_once('locationlist.class.php');

?>