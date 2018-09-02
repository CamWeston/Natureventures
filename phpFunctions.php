<?php
//############################################################################
// find name in uvm directory      
function ldapName($uvmID) {
    if (empty($uvmID))
        return "no:netid";

    $name = "not:found";

    $ds = ldap_connect("ldap.uvm.edu");

    if ($ds) {
        $r = ldap_bind($ds);
        $dn = "uid=$uvmID,ou=People,dc=uvm,dc=edu";
        $filter = "(|(netid=$uvmID))";
        $findthese = array("sn", "givenname");

        // now do the search and get the results which are stored in $info
        $sr = ldap_search($ds, $dn, $filter, $findthese);
        
        // if we found a match (in this example we should actually always find just one
        if (ldap_count_entries($ds, $sr) > 0) {
            $info = ldap_get_entries($ds, $sr);
            $name = $info[0]["givenname"][0] . ":" . $info[0]["sn"][0];
        }
    }

    ldap_close($ds);

    return $name;
}
//clean string of special chars to avoid sql error
            function clean($str) {
            //first get position of the extension in the string. Need to do this because the . in the extension is recognized as a special char
             $extNum = strrpos($str,".");
             //save extension string to be added at the end
             $ext = substr($str,$extNum);
             //get count of extension string
             $extNum = strlen($ext);    
             //this number will tell us how many chars to remove at the end of our string
             $extNum = 0 - $extNum;
            //get our string without the extension
             $str = substr($str,0,$extNum);
             //remove all special chars in our string 
             $str = preg_replace('/[^A-Za-z0-9\-]/', '', $str);
             //add the extension back to the end of the string
             $str = $str.$ext;
             //return the string
             return $str; 
                                 }
        function fileUpload($file,$location){
            

        }

?>