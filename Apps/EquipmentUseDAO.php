<?php

namespace Apps;

class EquipmentUseDAO
{
  /**
  * Data Access Object
  * Because static methods are callable without an instance of the object created,
  * the pseudo-variable $this is not available inside the method declared as static.
  */

  function __construc()
  {

  }

  static function getEquipmentUseDetails($dbConnection, $invoiceId)
  {
    $allDetails = [];
    $useDetailsObject = new EquipmentUseDetails();

    $query = "SELECT CII.invoice, CII.details, CII.service_id, CII.total, CI.filename, CI.payer, P.email, concat_ws(' ', P.first_name, P.last_name) AS name
    FROM core_invoice_item AS CII, core_invoice AS CI, people AS P
    WHERE CII.invoice = ? AND CII.invoice = CI.number AND P.individual_id = CI.payer";

    if($results = $dbConnection->prepare($query)){
      $results->bind_param("s", $invoiceId);
      $results->execute();
      /* bind result variables */
      $results->bind_result($invoice, $details, $service_id, $total, $filename, $payer, $email, $name);

      /* fetch values */
      while($results->fetch()){
        //Create an array of each user as objects
        //check whether data is already in object
        if(!$useDetailsObject->userName and $name)
        {
           $useDetailsObject->userName = $name;
           $useDetailsObject->email = $email;

          array_push($useDetailsObject->serviceInfoArray, $useDetailsObject->details = $details); #, $row['total']);
            if(!in_array($filename, $useDetailsObject->attachmentArray)){
          array_push($useDetailsObject->attachmentArray, $useDetailsObject->fileName = $filename);
          }
         $useDetailsObject->invoiceNumber = $invoice;
         $useDetailsObject->service_id = $service_id;
         $useDetailsObject->total = $total;
        }elseif($useDetailsObject->userName == $name) {
          array_push($useDetailsObject->serviceInfoArray, $useDetailsObject->details = $details);
          if(!in_array($filename, $useDetailsObject->attachmentArray)){
            array_push($useDetailsObject->attachmentArray, $useDetailsObject->fileName = $rowfilename);
          }
        }

      }
    }
    /* close statement */
    $results->close();
    //var_dump($results);
    return($useDetailsObject);
  }

}
