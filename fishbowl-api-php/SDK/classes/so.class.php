<?php
/**
  *  Script:        so.class.php
  *  Module:        Classes
  *  Written By:    Dave Newsom
  *  Created:       June 27, 2008
  *
  *  Description:   Utility routines for Fishbowls SDK - SO Module
  *
  */

class SO extends Fishbowl_SDK {

    // Additional fields being added by Sales Orders
    var $soid, $customerid;


    function CreateSO($data) {
        $xml = "<SalesOrder>
                    <Note>". $data['notes'] . "</Note>
                    <TotalPrice>" . $data['subtotal'] . "</TotalPrice>
                    <TotalTax>" . $data['tax'] . "</TotalTax>
                    <PaymentTotal>" . $data['payment'] . "</PaymentTotal>
                    <Salesman>Website</Salesman>
                    <Number>" . $data['order_num'] . "</Number>
                    <Status>20</Status>
                    <CreatedDate>" . $data['datecreated'] . "</CreatedDate>
                    <IssuedDate>" . date() . "</IssuedDate>
                    <TaxRatePercentage>" . $data['taxrate'] . "</TaxRatePercentage>
                    <TaxRateName>" . $data['taxname'] . "</TaxRateName>
                    <ShippingCost>" . $data['shipping'] . "</ShippingCost>
                    <CustomerName>" . $data['customer'] . "</CustomerName>
                    <Bill>
                        <Address>
                            ALL ADDRESS INFORMATION
                        </Address>
                    </Bill>
                    <Ship>
                        <Address>
                            ALL ADDRESS INFORMATION
                        </Address>
                    </Ship>";
        foreach ($data['items'] AS $soitem) {
            $xml = "<SalesOrderItem>
                        <ProductNumber>" . $soitem['product'] . "</ProductNumber>
                        <Description>" . $soitem['description'] . "</Description>
                        <Quantity>" . $soitem['qty'] . "</Quantity>
                        <ProductPrice>" . $soitem['price'] . "</ProductPrice>
                        <TotalPrice>" . $soitem['totalprice'] . "</TotalPrice>
                        <UOMCode>" . $soitem['uom'] . "</UOMCode>
                        <ItemType>10</ItemType>
                        <Status>10</Status>
                    </SalesOrderItem>";
        }
        $xml = "</SalesOrder>";

        // Pack for sending
        $len = strlen($xml);
        $packed = pack("N", $len);

        // Send stuff
        fwrite($this->id, $packed, 4);
        fwrite($this->id, $xml);
        $this->result = fread($this->id, 9216);
        $this->result = substr($this->result, 4);
    }

}


?>