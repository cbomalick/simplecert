<?php

require_once('customer.header.inc.php');

Echo"
<div class=\"boxwrapper\">
    <div class=\"customertabs full\" style=\"padding-top: 0px;\">
        <ul>
            <li class=\"customertabcurrent\"><a href=\"customer/view/{$id}\">Overview</a></li>
            <li><a href=\"customer/contacts/{$id}\">Contacts</a></li>
            <li><a href=\"customer/notes/{$id}\">Notes</a></li>
            <li><a href=\"customer/notes/{$id}\">Ledger</a></li>
            <li><a href=\"customer/notes/{$id}\">Recurring Fees</a></li>
            </ul>
    </div>
</div>";

Echo"<div class=\"boxwrapper\">";

//Customer Details box
Echo"
<div class=\"box mediumbox\">
        <h2>Customer Details</h2>
        <div class=\"boxcontent table\">
            <table class=\"table padded sortable\">
                <thead>
                    <tr>
                        <th class=\"\">Detail</th>
                        <th class=\"\">Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"bold\">Customer Since</td>
                        <td class=\"\">".date("m/d/Y", strtotime($customer->startDate))."</td>
                    </tr>
                    <tr>
                        <td class=\"bold\">Customer Status</td>
                        <td class=\"\">{$customer->status}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>";

//Recent Services box
    Echo"<div class=\"box fullbox\">
    <h2>Recent Billing</h2>
        <div class=\"boxcontent table\">
        <table class=\"table padded sortable\">
                        <thead>
                           <tr>
                              <th class=\"\" style=\"width: 12%; white-space: nowrap;\">Bill Date</th>
                              <th class=\"\" style=\"width: 12%; white-space: nowrap;\">Post Date</th>
                              <th class=\"\" style=\"width: 56%;\">Description</th>
                              <th class=\"\" style=\"width: 10%; white-space: nowrap;\">Bill Total</th>
                              <th class=\"\" style=\"width: 10%;\">Unpaid</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td class=\"\">09/23/2021</td>
                              <td class=\"\">10/01/2021</td>
                              <td class=\"\">Monthly Bill</td>
                              <td class=\"\">$16.23</td>
                              <td class=\"\">$0.00</td>
                           </tr>
                           <tr>
                              <td class=\"\">10/21/2020</td>
                              <td class=\"\">11/01/2021</td>
                              <td class=\"\">Monthly Bill</td>
                              <td class=\"\">$25.75</td>
                              <td class=\"\">$0.00</td>
                           </tr>
                           <tr>
                              <td class=\"\">10/23/2020</td>
                              <td class=\"\">11/01/2021</td>
                              <td class=\"\">Credit To Account (Reason: Service not delivered as described)</td>
                              <td class=\"\">($25.75)</td>
                              <td class=\"\">$0.00</td>
                           </tr>
                           <tr>
                              <td class=\"\">11/15/2020</td>
                              <td class=\"\">12/01/2021</td>
                              <td class=\"\">Monthly Bill</td>
                              <td class=\"\">$59.70</td>
                              <td class=\"\">$0.00</td>
                           </tr>
                           <tr>
                            <td class=\"\" colspan=\"3\"></td>
                            <td class=\"text-right bold\">Total Due:</td>
                            <td class=\"bold\">$0.00</td>
                         </tr>
                        </tbody>
                     </table>";
        
        Echo"</div>
        </div>
    </div>
    <button class=\"button\" onclick=\"window.location.href = '/customer';\">Go Back</button>";

?>