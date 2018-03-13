<?php
/**
 * survey_view.php is a page to demonstrate the proof of concept of the 
 * initial SurveySez objects.
 *
 * Objects in this version are the Survey, Question & Answer objects
 * 
 * @package SurveySez
 * @author William Newman
 * @version 2.12 2015/06/04
 * @link http://newmanix.com/ 
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see Question.php
 * @see Answer.php
 * @see Response.php
 * @see Choice.php
 */

# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials 

# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "news/topic.php");
}

# SQL statement
//$sql = "select MuffinName, MuffinID, Price from test_Muffins";
$sql = 
"select feedID, feedName, feedURL from feeds where feedID = $myID";



#Fills <title> tag. If left empty will default to $PageTitle in config_inc.php  
$config->titleTag = 'Feed';
#END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to theme header or header_inc.php
?>

<?php
/*

    
    

     
    
    d

*/
#images in this case are from font awesome
$prev = '<i class="fa fa-chevron-circle-left"></i>';
$next = '<i class="fa fa-chevron-circle-right"></i>';

$today = date("Y-m-d H:i:s");

# Create instance of new 'pager' class
$myPager = new Pager(20,'',$prev,$next,'');
$sql = $myPager->loadSQL($sql);  #load SQL, add offset

# connection comes first in mysqli (improved) function
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

if(mysqli_num_rows($result) > 0)
{#records exist - process
	if($myPager->showTotal()==1){$itemz = "news";}else{$itemz = "news";}  //deal with plural
    
	while($row = mysqli_fetch_assoc($result))
	{# process each row

        //read-feed-simpleXML.php
        //our simplest example of consuming an RSS feed
          $request = $row['feedURL'];
 
    }//end while
    


          $response = file_get_contents($request);
          $xml = simplexml_load_string($response);
          print '<h1>' . $xml->channel->title . '</h1>';
          foreach($xml->channel->item as $story)
          {
            echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
            echo '<p>' . $story->description . '</p><br /><br />';
          }
          


    
    startSession();   
    if(!isset($_SESSION['Feeds'])){
       $_SESSION['Feeds'] = array(); 
    }
    
    if(!feedExist($_SESSION['Feeds'], $myID)){
        
        $_SESSION['Feeds'][] = new Feed($myID,$today,$response);
        
    }else{
        /*
        if time is greater than 10 minutes than load new 
        else is time is less than 10 minutes use the cache and don't load new
        */
    
        
    }
    
    dumpDie($_SESSION['Feeds']);
    
    echo '</tbody>
        </table>';
    
	echo $myPager->showNAV(); # show paging nav, only if enough records	 
}else{#no records
    echo "<div align=center>There are currently no news</div>";	
}
@mysqli_free_result($result);

get_footer(); #defaults to theme footer or footer_inc.php
class Feed{
    public $feedID = '';
    public $dateTime = '';
    public $feedURL = 0;
    
    public function __construct($feedID,$dateTime,$feedURL){
        $this->feedID = $feedID;
        $this->dateTime = $dateTime;
        $this->feedURL = $feedURL;
    }//end Duck constructor
    
    public function __toString(){
        setlocale(LC_MONETARY,'en_US');
        $Allowance = money_format('%i',$this->Allowance);

        $myReturn = '';
        $myReturn .= 'Name: ' . $this->Name . ' ';
        $myReturn .= 'Hobby: ' . $this->Hobby . ' ';
        $myReturn .= 'Allowance: ' . $Allowance . ' ';

        return $myReturn;
    }//end toString()

}//end Duck class

function feedExist($ar, $ID){
    foreach($ar as $feed){
        if ($ID == $feed->ID){
            return true;
        }
        
    }
    
   return false; 
}
?>
