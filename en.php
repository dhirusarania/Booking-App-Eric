<?php

$data = array();




$data = array(
    
    //menu items
    'appointments' => "Agenda",
    'create_appointments' => "Create Appointments",
    'create_personal_appointments' => "Personal Appointments",
    'overview' => "Overview",
    'shortcuts' => "Shortcuts",
    'filters' => "Filters",
    'appointment_state' => "Appointment State",
    
    //dashboard
    'select_date' => "Select Date",
    'zoom_in' => "Zoom In",
    'zoom_out' => "Zoom Out",
    
    //update Appointment Popup
    'selected_appointment' => "Selected Appointment",
    'customer_name' => "Customer Name",
    'service' => "Service",
    'current_time' => "Current Time",
    'duration' => "Duration",
    'date' => "Date",
    
    //update personal Appointment Popup
    'employees' => "Employees",
    'appointment_type' => "Appointment Type",
    'description' => "Description",
    'duration' => "Duration",
    'date' => "Date",


    'delete' => 'Delete',
    'close' => 'Close',
    'save_appointment' => 'Save Appointment'
    
);



$res = json_encode($data);
echo $res;
exit;

?>