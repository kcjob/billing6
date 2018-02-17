<?php
namespace Apps;

class InvoiceSender
{

  static function sendInvoice($connection, $invoiceIdArray){
    foreach($invoiceIdArray as $invoiceId){
        try {
            $useDetailsObject = EquipmentUseDAO::getEquipmentUseDetails($connection, $invoiceId);
            //print_r($useDetailsObject);
        } catch (Exception $e) {
            $log->error($e->getMessage());
            echo "Problem retrieving data from database\r\n";
            die();
        }

        try{
            $emailMessage = EmailMessageGenerator::createEmail($useDetailsObject);
        } catch(Exception $e){
            $log->error($e->getMessage());
            echo "Could not generate email message\r\n";
            die();
        }
        try{
             EmailMessageSender::sendEmail($emailMessage);
        } catch(Exception $e){
            $log->error($e->getMessage());
            echo "Could not send email message\r\n";
            die();
        }
    } //for
  }
}
