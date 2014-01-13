// Use this Javascript to control the applet functionality and appearence
// You have over 130 parameters that you can control, consult documentation.html for details
// Please make sure that you escape all reserved characters with a backslash. 
// For example: " should be entered as \"
// After modifying this file, make sure that you clear your browser cache before you test



var width = "700";  //Integer value in pixels
var height = "550";  //Integer value in pixels


var parameters = {


    // Enter you keys here , if you have only 1 key enter key1 and leave the other lines empty
    key1				: "",
    key2				: "",
    key3				: "",
    key4				: "",
    key5				: "",
    key6				: "",

    // Connection related values
    server 			: "infocloud.server",  //host name or IP of FTP server -- replace this with the actual hostname or IP of your server
    port 			: "21",  //The port number of the FTP server. Default 21
    user 			: "username",  //The username for the FTP server account
    pass 			: "password",  //The password for the FTP server account
    autoconnect		: "true",  //If true, will connect based on above settings and hide connect/disconnect buttons.  Default false
    autoreconnect		: "true",  //If true, will not prompt for reconnect.  Default true
    passive 		: "",  //Toggle passive or active connection.  Default true
    encrypt			: "",  //encrypt and ek paramters can be used to scramble, host username and password so it can not be readable via view source
    ek			: "",
    connecttimeout		: "",  //value in ms for connection attempt. Default 20000
    sotimeout		: "",  //value in ms to wait for read before timing out. Default -1 (no timeout)
    waitRetry 		: "",  //value in ms to wait before attempting reconnect. Default 3000
    maxRetries 		: "",  //number of times to attempt retry. Default 1
    bufSizeGet 		: "",  //This value specifies the size of the transfer buffer UnlimitedFTP will use when downloading files
    bufSizePut 		: "",  //This value specifies the size of the transfer buffer UnlimitedFTP will use when uploading files
    useSerfo		: "",  //see documentation.  Default false
    SerfoLocation		: "",  //see documentation.  Default not used
    enableHTTPResume 	: "",  //see documentation, requires useSerfo to be true
    characterEncoding 	: "",

    // UnlimitedFTP Secure paramters only, leave these empty if you are using UnlimitedFTP Pro
    protocol 		: "",  // "FTP", "FTPS", or "SFTP" Default FTP
    ftpstype 		: "",  // "EXPLICIT" or "IMPLICIT"  (FTPS only)
    securedata 		: "",  // "NONE", "EXPLICIT" or "IMPLICIT"  (FTPS only)
    authCommand		: "",  // "AUTH_SSL" or "AUTH_TLS"  (FTPS only)
    pkLoc			: "",  //Fully qualified path to a private key on the local file system (SFTP only)
    pkPass			: "",  //password for private key set in pkLoc parameter  (SFTP only)

    // Proxy related settings 
    autodetectproxy		: "",  //If true, automatically attempt to detect IE's Proxy setup.  Default false
    socksproxy		: "",  //If true, use the SOCKS proxy server and port specified below.  Default false
    socksProxyHost		: "",  //SOCKS proxy server location
    socksProxyPort		: "",  //SOCKS proxy server port
    ftpproxy		: "",  //If true (and socksproxy false), use FTP proxy specified below.  Default false
    ftpProxyHost		: "",  //FTP proxy server location
    ftpProxyPort		: "",  //FTP proxy server port

    // Functionality related values
    ascbin			: "",  //Default transfer mode (asc, bin, auto).  Default bin
    asciiextensions		: "",  //Transfer all the files with these extensions in Ascii Mode (comma delimited list) if ascbin is auto
    extensions		: "",  //Only files with these extensions will be displayed/affected by UnlimitedFTP
    exclude			: "",  //exclude files or directories matching pattern (comma delimited list)
    invertExclude 		: "",  //If true, change exclude list to an include-only list.  Default false
    lockinitialdir		: "",  //If true, lock user to initial directory and its subdirectories.  Default false
    localdir 		: "",  //switch to this local directory when UFTP loads (<directory>, HOME, DESKTOP, ROOT)
    remotedir 		: "",  //switch to this directory after login.  Eg "/initialdir"
    createdirectoryonconnect: "",  //create this directory after login.  Eg "/mydirectory"
    enableCookies		: "",  //If true, UFTP will store cookies to remember the last site and username.  Default true
    doubleClickTransfer	: "",  //If true, double-clicking file will initiate transfer. Default true
    enablerightclick	: "",  //If set to false, right-click menu disabled for end user.  Default true
    enablekeyboardshortcuts	: "",  //If changed to false, keyboard shortcuts disabled for end user.  Default true
    confirmoverwrite	: "",  //If true, prompt for overwrite if file exists.  Default true
    confirmTransfer 	: "",  //If true, prompt before each upload or download.  Default false
    syncpriority		: "",  //When using Sync function, this parameter determines which is master (local, remote) Default local
    incremental		: "",  //If true, files that have not been modified locally will not be transferred.  Default false
    incdatetime		: "",  //If true, use time and date to determine file modification.  Default false
    timezonecomp		: "",  //A value (in minutes) for calculating time zone difference.  Default 0
    selectalllocal		: "",  //If true, all files in initial local directory will be selected when UnlimitedFTP initializes.   Defalt false
    selectallremote		: "",  //If true, all files in initial remote directory will be selected after connection.  Default false
    selectfileremote    	: "",  //Specified filename will be selected after connection.  Default none
    autoupload		: "",  //If selectalllocal iable is used, automatically upload files after connection.  Default false
    autodownload		: "",  //If selectallremote/selectfileremote are used, automatically download these after connection.  Default false
    autoallo		: "",  //If true, ALLO command sent to server.  Not all FTP servers implement ALLO.  Default false
    hostsAllowed		: "",  //List of URLs to limit hosts and IPs allowed for connection.  Default no limitation
    totalProgress		: "",  //If false, show progress of individual files instead of entire upload.  Default true
    enableResume 		: "",  //(true, false, auto).  Prompt user to attempt resume.  Default true
    deleteoncancel		: "",  //If true, partial transfers deleted on cancel.  Default true
    customFileOptions 	: "",  //Customize right-click file options.  See documentation
    customDirOptions 	: "",  //Customize right-click directory options.  See documentation
    sendLogsToURL 		: "",  //make HTTP POST to specified URL with FTP log.   Default none
    preserveModificationTime: "",  //if true, source time and date is written to the destination.  Default false
    removeAccentsAndQuotes	: "",  //if true, accented letters and quotation marks are removed from filename at destination.  Default false
    removeSpaces		: "",  //if true, all spaces in the filename are removed at destination.  Default false
    helplocation	    	: "documentation.html",  //Specify help file location.

    // Values that effect the color of the client.  All values specified as decimal RGB values (eg. 0,0,0)
    background 		: "",  //default 255,255,255
    buttonTextColorOnMouseOver: "",  //default 255,255,255	
    buttonTextColor		: "",  //default 0,0,0
    buttonColorOnMouseOver	: "",  //default 10,50,10
    buttonbackground	: "",  //default 81,132,81
    headerTextColor		: "",  //default 0,0,255
    headerBackgroundColor	: "",  //default same as background iable
    drivesForegroundColor 	: "",  //default 0,0,0
    drivesBackgroundColor 	: "",  //default 255,255,255
    ascBinTextColor 	: "",  //default 0,0,0

    // values that effect the interface layout of the client
    language 		: "",  //See documentation.html
    classicfilelist		: "",  //If true, use default VM file list. Default false
    useToolbar		: "",  //Set to false to revert to legacy GUI. Default true
    useBottomToolbar	: "",  //Set to false to revert to legacy GUI. Default true
    LocalOptions		: "",  //Enable or disable local option buttons. Set to false to hide all.  Default all visible
    RemoteOptions		: "",  //Enable or disable remote option buttons. Set to false to hide all.  Default all visible
    remoteheader		: "",  //Specify text to show above remote list.  Default to hostname/IP
    stretchButtons		: "",  //For legacy GUI only. Stretch function buttons to fill empty space.  Default true
    display			: "",  //If false, hide the FTP message display area. Default true
    showsizeanddate		: "true",  //If true, show details next to filename. Default false
    showascbin		: "",  //Set false to hide ascii/binary radio button on GUI. Default true
    showhelpbutton		: "",  //Set false to hide help button on GUI. Default true
    showputbutton		: "",  //Set false to hide upload button on GUI. Default true
    showgetbutton		: "",  //Set false to hide download button on GUI. Default true
    showsyncbutton		: "",  //Legacy GUI only.  Set false to hide sync button on GUI. Default true
    showaboutbutton		: "",  //Set false to hide help button on GUI. Default true
    showconnectbutton 	: "",  //Set false to hide connect button on GUI. Default true
    showdisconnectbutton 	: "",  //Set false to hide disconnect button on GUI. Default true
    showlocallist		: "",  //Set false to hide local file list as well as local buttons.  Default true.
    showremotelist		: "",  //Set false to hide remote file list as well as remote buttons.  Default true.
    showSizeInKB		: "",  //Set true to display file size in KB instead of bytes.  Default false
    showlocaladdressbar	: "",  //Set false to hide local directory path field.  Default true
    showremoteaddressbar	: "",  //Set false to hide remote path field.  Default true
    showFileInfoBar 	: "",  //If true, show name, date, and size of file below local or remote list.  Default false
    showStatusBar 		: "",  //If true, display a status bar for connectivity and security.  Default false
    showTree		: "",  //If false, only content of current directory is shown.  Default true

    // values that change the options appearing in the connect dialog, UnlimitedFTP Secure ONLY
    showAdvancedTab 	: "",
    showSitename 		: "",
    showHostname 		: "",
    showUsername 		: "",
    showPassword 		: "",
    showAnonymous 		: "",
    showSaveConnection 	: "",

    // customizable error pages.  Will redirect to specified page for each situation.  Most are legacy iables.
    rejectPermissionURL : "rejectPerms.html", //User has rejected security certificate or has not trusted applet
	
	// Use this to add additional Java arguments to increase Memory, etc...		
	java_arguments		    	:"-Xmx512m -Djava.net.preferIPv4Stack:true"

};// end of var paramters

var jar = "unlimitedftp.jar";
var classname = "unlimited.ftp.UnlimitedFTPPlugin";


var attributes = {
    name: "UnlimitedFTP Pro",
    code: classname,
    archive: jar,
    width:width,
    height:height
};

var version = '1.4';

if (typeof(deployJava) == 'undefined'){
     document.write("<h2>deployJava.js is missing, make sure that you also include deployJava.js in your html</h2>");
 }else{
    deployJava.runApplet(attributes, parameters, version);
}
