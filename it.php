<?php

$data = array();




$data = array(

    //login
    'appointments' => "Appointments",
    'username' => "nome utente",
    'password' => "password",
    'change_password' => "Cambia password",


    "filters" => "Filtri",

    "appointment_state" => "Mostra",

    "color_appointment" => "Colori appuntamenti",

    "customer_name" => 'Cliente',
    "customer_email" => 'Customer Email',
    "customer_phone" => 'Customer Phone',


    "service" => "Servizio",
    "employee" => "Staff",
    "available_appointment_time" => "Orari disponibili",
    "no_free_slots" => "Nessun orario disponibili",
    "close" => "Chiudi",
    "save" => "Save",
    "create_appointment" => "Salva",
    "delete" => "Elimina",
    "update_appointment" => "Salva",
    "selected_appointment" => "Salva",


    //personal appointment
    'personal_appointments' => 'Appuntamento personale',
    'employees' => 'Staff',
    'appointment_type' => 'Tipo appuntamento',
    'description' => 'Descrizione',
    'duraration' => 'Durata  in minuti',
    'date' => 'Data',
    'save_changes' => 'Salva',


    //menu items
    'appointments' => "Appointments",
    'create_appointments' => "Nuovo appuntamento",
    'create_personal_appointments' => "Appuntamento personale",
    'overview' => "Overview",
    'shortcuts' => "Shortcuts",

    //dashboard
    'select_date' => "Select Date",
    'zoom_in' => "Zoom In",
    'zoom_out' => "Zoom Out",

    //update Appointment Popup
    'current_time' => "Current Time",
    'duration' => "Duration",

    //update personal Appointment Popup

    'save_appointment' => 'Save Appointment'

);



$res = json_encode($data);
echo $res;
exit;
