<?php 
class Message {
  // The message type e.g. Error, Warning, Info
  var $type;

  // The text of the message
  var $message;

  ///////////////////////////////////////////////////////
  // Constructor
  /////////////////////////////////////////////////////// 
  function Message($t, $m) {
    $this->type = $t;
    $this->message = $m;
  }

  ///////////////////////////////////////////////////////
  // Get message
  ///////////////////////////////////////////////////////
  function getMessage() {
    return $this->message;
  }
 
  ///////////////////////////////////////////////////////
  // Get type
  ///////////////////////////////////////////////////////
  function getType() {
    return $this->type;
  }
}

/********************************************************
 * Message class for displaying error and status messages
 * to the user.
 ********************************************************/
class MessageList {
  // Array of current messages.
  var $currentMessages;


  ///////////////////////////////////////////////////////
  // Constructor
  ///////////////////////////////////////////////////////
  function MessageList() {
    $this->currentMessages = array();
  }

  ///////////////////////////////////////////////////////
  // Display the current messages
  ///////////////////////////////////////////////////////
  function displayCurrentMessages() {
   echo (count($this->currentMessages)." messages");
   if (count($this->currentMessages) == 0) {
      return;
    }
    echo "<table>";
    while (count($this->currentMessages)>0) {
      $msg = array_pop($this->currentMessages);
      echo "<tr><td class='TBLROW'><font class='TBLROW'>".$msg->getMessage()."</font></td></tr>";
    }
    echo "</table>";
  }

  ///////////////////////////////////////////////////////
  // Clear the current messages
  ///////////////////////////////////////////////////////
  function clearCurrentMessages() {
    $this->currentMessages = array();
  }

  ///////////////////////////////////////////////////////
  // Add New message
  ///////////////////////////////////////////////////////
  function addNewMessage($typ,$msg) {
    $msg = new Message($typ,$msg);
    $this->addMessage($msg);
  }

  ///////////////////////////////////////////////////////
  // Add message
  ///////////////////////////////////////////////////////
  function addMessage($msg) {
    $this->currentMessages[0] = $msg;
    array_push($this->currentMessages,$msg);
  }
}
?>
