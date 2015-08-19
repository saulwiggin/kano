<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>User Stories</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">

  <link rel="stylesheet" href="css/styles.css?v=1.0">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">



  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  
  <style>
	tbody td {
	  background-color: white;
	}
	tbody td.odd {
	  background-color: #666;
	  color: white;
	}
  </style>
</head>

<body>
  <script src="js/scripts.js"></script>
  <?php 
  
  $url="http://api.kano.me/share?limit=100";
	//  Initiate curl
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);
	
//	var_dump(json_decode($result, true));

$item_array = json_decode($result);
// print_r($item_array);



	?>
  <h1> Latest 100 Shares <img style="height:80px" src="http://pong.kano.me/assets/kano-logo.png">   </h1> 
  <br>
  <form>
  <input type="text" id="searchbox" placeholder="insert search title">
  <input type="submit" value="Submit">
  </form>
  <br>
  
  <p> <h4> Search by: </h4>
  <button type="button" id="sort-time">Time</button>
  <button type="button" id="sort-title">Likes</button>
  <button type="button" id="sort-likes">Title</button>
  </p>
  
  <div class="table-responsive">
    <table class="table">
	  <thead>
	  <th>  Picture	  </th>	  <th>	  Title	  </th>	  <th>	  author's username	  </th>	  <th>	  number of likes	  </th>	  <th hidden="true">	 Likes
	  </th>
	  
	  </thead>
	  <tbody id ="searchtable">
		  <?php foreach ($item_array->values as $item) ?>
		  <tr>
			  <td>
				<a href="details_page.html" ><img id="thumbnail" height="80px" src=" . <?php echo $item[attachment_url] ?> . "></a>
			  </td>
			  <td>
				<?php echo $item ?>
			  </td>
			  <td>
				<?php echo $item->entries->user->username ?>
			  </td>
			  <td>
				<?php echo $item["views_count"] ?>
			  </td>
			  <td>
				<?php echo $item["views_count"] ?>
			  </td>
			  <td>
				<?php echo $item[likes] ?>
			  </td>
			    <?php end ?>
		  <tr>
	  </tbody>
	</table>
</div>
  
</body>

<script>
$("#searchbox").keyup(function () {
    var rows = $("#searchtable").find("tr").hide();
    if (this.value.length) {
        var data = this.value.split(" ");
        $.each(data, function (i, v) {
            rows.filter(":contains('" + v + "')").show();
        });
    } else rows.show();
});


//sort the table hears into time, number of likes,etc
var $ = function( id ) {  return document.getElementById( id ); },
    $$ = function( query , base ) { 
        return ( base||document).querySelectorAll( query ); 
    },
    table = $('table'),
    rows = table.rows;

function getDate( str ) {

    var ar =  /(\d{2})-(\d{2})-(\d{4}) (\d{2}):(\d{2}):(\d{2}) ([AM|PM]+)/ 
         .exec( str ) 
      
    return new Date(
        (+ar[3]),
        (+ar[2])-1, // Careful, month starts at 0!
        (+ar[1]),
        (+ar[4])+ ( ar[7]=='PM'? 12 :0 ),
        (+ar[5]),
        (+ar[6])
    );
}

function updateRows( rows) {
    var tbody = $$('tbody')[0];
    while (tbody.hasChildNodes()) {
        tbody.removeChild(tbody.lastChild);
    }
    console.log( rows );
    for ( var i =0; i< rows.length ;i++ ){
          tbody.appendChild( rows[i] );  
    }
}

function getSortable(){
    
    var trows = $('table').rows ,
        rows =[];    
    
    for( var i =1; i< trows.length; i++ ){
        rows.push( trows[i] );
    }
    
    return rows;
}

function sortbyTitle( el ){
    el.sort = ! el.sort;
    var rows = getSortable();    
    [].sort.call( rows, function( a, b ){
        var aVal =  $$('td', a )[2].innerHTML ,
            bVal = $$('td', b )[2].innerHTML;
        return el.sort? aVal < bVal : aVal > bVal;  
    });    
    updateRows( rows ); 
}


function sortbyTime( el ){
    el.sort = ! el.sort;
    var rows = getSortable();    
    [].sort.call( rows, function( a, b ){
        var aVal =  getDate( $$('td', a )[1].innerHTML ) ,
            bVal = getDate(  $$('td', b )[1].innerHTML );
        return el.sort? aVal < bVal : aVal > bVal;  
    });    
    updateRows( rows );
}

function sortbyNumberoflikes( el ){
    el.sort = ! el.sort;
    var rows = getSortable();    
    [].sort.call( rows, function( a, b ){
        var aVal =  +$$('td', a )[0].innerHTML ,
            bVal = +$$('td', b )[0].innerHTML;
        return el.sort? aVal < bVal : aVal > bVal;  
    });    
    updateRows( rows );
}

$('sort-time').addEventListener('click', function(){
    sortbyTime( this );
},false);

$('sort-title').addEventListener('click', function(){
    sortbyTitle( this );
},false);

$('sort-likes').addEventListener('click', function(){
    sortbyNumberofLikes( this );
},false);

</script>
</html>