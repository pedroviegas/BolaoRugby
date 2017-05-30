<?php 

//////////////////////////////////////////////////////////////
// Modify the values from here to where you are told to stop.
// The numbers match those in the installation steps in
// the file INSTALL.TXT.
//////////////////////////////////////////////////////////////
//////////////////////////////////////////
// 1.Prediction League Title
// The title of the Prediction League.
// Change the value between the "" to 
// give the prediction league the title
// of your choice
//////////////////////////////////////////
$PredictionLeagueTitle = "Prediction Football.com";

//////////////////////////////////////////////////////////////
// 2. Header Row
// This is the header to be displayed in 
//all the pages. It can contain HTML code.
//////////////////////////////////////////////////////////////
$HeaderRow = "<script type=\"text/javascript\">function getdate() { return \"tuesday\";}</script><table cellpadding=\"0\" cellspacing=\"0\"><tr><td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"><img border=\"0\" src=\"banner.jpg\"></font></td></tr></table>";

//////////////////////////////////////////////////////////////
// 3. Database hostname
// Database hostname
// The fqdn of the host containing the
// database
//////////////////////////////////////////////////////////////
$dbaseHost = "localhost";

//////////////////////////////////////////////////////////////
// 4. Base Directory Name
// The directory storing the prediction
// league files
//////////////////////////////////////////////////////////////
$baseDirName = "PredictionLeague";

//////////////////////////////////////////////////////////////
// 5. Username
// User name
// The username to be used for logging
// into the database
//////////////////////////////////////////////////////////////
$dbaseUsername = "username";

//////////////////////////////////////////////////////////////
// 6. Password
// Password to be used for logging into
// the database
//////////////////////////////////////////////////////////////
$dbasePassword = "password";

//////////////////////////////////////////////////////////////
// 7. Database Name.
// This is the name of the database. This *MUST* be the same
// name as the name you used when creating the database.
//////////////////////////////////////////////////////////////
$dbaseName = "predictionfootball";

//////////////////////////////////////////////////////////////
// 8. The email address of the administrator. Set this to your 
// own address
//////////////////////////////////////////////////////////////
$adminEmailAddr = "";

//////////////////////////////////////////////////////////////
// 9. The signature of the admin to use at the end of the 
//    email welcoming the user. This can be a simple name,
//    or something more complex.
//////////////////////////////////////////////////////////////
$adminSignature = "\nhttp://www.predictionfootball.com/";

//////////////////////////////////////////////////////////////
// 10. The default icon to use for a new user. The icons are
// displayed when the user is logged on. If you have an icon
// named default.gif, you can leave this as default.gif.
//////////////////////////////////////////////////////////////
$defaulticon = "default.gif";

//////////////////////////////////////////////////////////////
// 11. The URL of the associated chat room.
// This link can be used to point to chatroom, or discussion
// area you may have for your prediction league.
// If this is empty, the menu link is not shown.
//////////////////////////////////////////////////////////////
$chatRoomURL = "";

//////////////////////////////////////////////////////////////
// 12. The URL of the associated home page
// Add the URL of your home page. This is shown in the menu.
//////////////////////////////////////////////////////////////
$homePage = "http://www.predictionfootball.com";
$homePageTitle = "Prediction Football";

//////////////////////////////////////////////////////////////
// 13. The name of the log file. 
// "" disables the logfile functionality.
//////////////////////////////////////////////////////////////
$logfile = "logfile.txt";

//////////////////////////////////////////////////////////////
// 14. To allow more than one user per email address set
//     this variable to TRUE .
//     e.g. $allowMultipleUserPerEmail = "TRUE";
//////////////////////////////////////////////////////////////
$allowMultipleUserPerEmail = "FALSE";

//////////////////////////////////////////////////////////////
// 15. If your server is in a different timezone than the
//     country in which the games are played then enter the 
//     difference in hours here.
//     This does not allow for differences when daylight 
//     savings times are encountered.
//     e.g. Server is in Wash DC, USA and league in UK. Then
//     $timezoneOffset = -5.
//////////////////////////////////////////////////////////////
$timezoneOffset = 0;

//////////////////////////////////////////////////////////////
// 16. Set this flag to true to show the fixtures in reverse
//     date order in ShowMyPredictions. Setting this to
//     FALSE will display the fixtures in date order, first
//     date first.
//////////////////////////////////////////////////////////////
$reverseUserPredictions = "TRUE";
$reverseFixtures = "TRUE";

//////////////////////////////////////////////////////////////
// 17. Change this flag to define which language file to use.
//     Language files must be in subfolder lang.
//     Default value = "lang.english.php"
//////////////////////////////////////////////////////////////
$languageFile = "lang.english.php";
$direction = "ltr";

//////////////////////////////////////////////////////////////
// 18. Change this flag to enable auto predictions.
//     Auto predictions allow the user to have the scripts
//     make predictions for games where they have not made
//     there own prediction.
//////////////////////////////////////////////////////////////
$autoPredict = "FALSE";

//////////////////////////////////////////////////////////////
// 19. Use the 24 hour clock
//////////////////////////////////////////////////////////////
$Use24Hr = "FALSE";

//////////////////////////////////////////////////////////////
// 20. Allow users to upload icons.
// The MaxIconFileSize in in Kb. This for a maximum 50Kb
// image the value is set to 50.
//////////////////////////////////////////////////////////////
$UploadIcons = "TRUE";
$MaxIconFileSize = 50;

//////////////////////////////////////////////////////////////
// 21. Encrypt passwords.
// This variable determines whether encryption is enabled.
// The value is also configured in the config screen.
// If you previously chose not to encrypt passwords then you
// need to make sure that the users use the forgot password 
// feature to get their new passwords.
//////////////////////////////////////////////////////////////
$PasswordEncryption = "TRUE";

//////////////////////////////////////////////////////////////
// 22. LockGame
// This variable allows the game to be locked so that only
// power users can create users.
//////////////////////////////////////////////////////////////
$LockedGame = "FALSE";

//////////////////////////////////////////////////////////////
// 23. Audit email addresses. 
// All predictions will be sent to this/these addresses if 
// this value is set.
//////////////////////////////////////////////////////////////
$AuditEmailAddr = "";

?>