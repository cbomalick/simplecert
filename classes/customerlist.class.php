<?php

class CustomerList{

    public function __construct(){
        $this->connect = Database::getInstance()->getConnection();
        $this->session = Session::getInstance()->getSession();
    }

    public function newCustomerList($status, $dateFrom, $dateTo, $companyId, $customerName){
        $in  = str_repeat('?,', count($status) - 1) . '?';
        $customerName = "%".$customerName."%";

        $sql = "SELECT customerid FROM customer WHERE status IN ($in) 
        AND startdate BETWEEN ? AND ? AND company = ? AND accountname LIKE ? ORDER BY accountname ASC";
        $stmt = $this->connect->prepare($sql);
        $params = array_merge($status, [$dateFrom, $dateTo, $companyId, $customerName]);
        $stmt->execute($params);
        $row = $stmt->fetchAll();
        //Construct with parent and then add extra event stuff on top

        if(!empty($row)){
            foreach ($row as $row){
                //Pull metric details
                $customer = new Customer($row['customerid']);

                $customer->action = "
                    <select name = \"dropdown\" class=\"js-example-basic-single\" onchange=\"if (this.value) window.location.href=this.value\">
                    <option value=\"\"></option>";

                    if($this->session->loggedInUser->validatePermissions("CustomerView")){
                        $customer->action .= "<option value=\"customer/view/{$customer->customerId}\">View</option>";
                    }
                    if($this->session->loggedInUser->validatePermissions("CustomerEdit")){
                        $customer->action .= "<option value=\"customer/edit/{$customer->customerId}\">Edit</option>";
                    }
                    if($this->session->loggedInUser->validatePermissions("CustomerDelete")){
                        $customer->action .= "<option value=\"customer/cancel/{$customer->customerId}\">Cancel</option>";
                    }

                $customer->action .= "</select>";

                //Add to array
                $objList[] = $customer;
            }
            return $objList;
        } else {
            return null;
        }
    }

    public function printCustomerList($inputList){
        $headers = array(
        "Customer" => "accountName",
        "Primary Contact" => "primaryContactName",
        "Start Date" => "startDate",
        "Action" => "action");

        //CSS Styling for columns
        $classes = "action:short-input";

        $table = new Table($headers, $inputList, $classes);
    }
        
    }

?>