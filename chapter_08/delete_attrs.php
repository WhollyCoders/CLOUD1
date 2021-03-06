#!/usr/bin/php
<?php
/*
 * delete_attrs.php
 *
 *	Delete the Flavor and ModTime attributes from every
 *	item in a SimpleDB domain.
 *
 * Copyright 2009-2010 Amazon.com, Inc. or its affiliates. All Rights
 * Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"). You
 * may not use this file except in compliance with the License. A copy
 * of the License is located at
 *
 *       http://aws.amazon.com/apache2.0/
 *
 * or in the "license.txt" file accompanying this file. This file is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the
 * License.
 */

error_reporting(E_ALL);

require_once('sdk.class.php');
require_once('include/book.inc.php');

// Create the SimpleDB access object
$sdb = new AmazonSDB();

// Set list of attributes to delete
$attrs = array('ModTime', 'Flavor');

// Query for each file
$res1 = $sdb->select("select Name from " . BOOK_FILE_DOMAIN);
if ($res1->isOK())
{
  foreach ($res1->body->SelectResult->Item as $item)
  {
    $itemName = (string)$item->Name;
    
    $res2 = $sdb->delete_attributes(BOOK_FILE_DOMAIN, $itemName, $attrs);

    if ($res2->isOK())
    {
      print("Updated item $itemName\n");
    }
    else
    {
      $error = $res2->body->Errors->Error->Message;
      print("Could not update item: ${error}\n");
    }
  }
}
else
{
  $error = $res1->body->Errors->Error->Message;
  exit("Could not run query: ${error}\n");
}
exit(0);
?>
