<?php
/**
 * survey_view.php is a page to demonstrate the proof of concept of the 
 * initial SurveySez objects.
 *
 * Objects in this version are the Survey, Question & Answer objects
 * 
 * @package SurveySez
 * @author Bella Chen
 * @version 1.0 2018/02/06
 * @link http://www.example.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see Question.php
 * @see Answer.php
 * @see Response.php
 * @see Choice.php
 */

#'../' works for a sub-folder.  use './' for the root
#provides configuration, pathing, error handling, db credentials  
require '../inc_0700/config_inc.php';  

#check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
#proper data must be on querystring
if(isset($_GET['id']) && (int)$_GET['id'] > 0){
	 #Convert to integer, will equate to zero if fails
   $myID = (int)$_GET['id']; 
}else{
	myRedirect(VIRTUAL_PATH . "news/topic.php");
}

# SQL statement
//$sql = "select MuffinName, MuffinID, Price from test_Muffins";
$sql = "select feedID, feedName, feedURL from feeds where feedID = $myID";

#Fills <title> tag. If left empty will default to $PageTitle in config_inc.php  
$config->titleTag = 'Feed';

#END CONFIG AREA ---------------------------------------------------------- 

#defaults to theme header or header_inc.php
get_header(); 
?>

<?php

#images in this case are from font awesome
$prev = '<i class="fa fa-chevron-circle-left"></i>';
$next = '<i class="fa fa-chevron-circle-right"></i>';

# Create instance of new 'pager' class
$myPager = new Pager(20,'',$prev,$next,'');

#load SQL, add offset
$sql = $myPager->loadSQL($sql);  

# connection comes first in mysqli (improved) function
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

#records exist - process
if(mysqli_num_rows($result) > 0)
{
    //deal with plural
	if($myPager->showTotal()==1){$itemz = "news";}else{$itemz = "nes";}

    # process each row   
	while($row = mysqli_fetch_assoc($result))
	{
        //read-feed-simpleXML.php
        //our simplest example of consuming an RSS feed
        $request = $row['feedURL'];
        $response = file_get_contents($request);
        $xml = simplexml_load_string($response);
        print '<h1>' . $xml->channel->title . '</h1>';
        foreach($xml->channel->item as $story)
        {
            echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
            echo '<p>' . $story->description . '</p><br /><br />';
        }
    }
    echo '</tbody>
        </table>';
    # show paging nav, only if enough records
	echo $myPager->showNAV(); 	 
}else{
    #no records
    echo "<div align=center>There are currently no news</div>";	
}
@mysqli_free_result($result);

#defaults to theme footer or footer_inc.php
get_footer(); 
?>
