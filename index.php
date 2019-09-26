<?php


$servername = "localhost";
$username =  "admin";
$password = "Ycrep2016";
$dbname = "bdfactura";



$con = mysqli_connect($servername,$username,$password,$dbname) ;



class Paginator{

	var $items_per_page;

	var $items_total;

	var $current_page;

	var $num_pages;

	var $mid_range;

	var $low;

	var $limit;

	var $return;

	var $default_ipp;

	var $querystring;

	var $ipp_array;



	function Paginator()

	{

		$this->current_page = 1;

		$this->mid_range = 7;

		$this->ipp_array = array(10,25,50,100,150,200,'All');

		$this->items_per_page = (!empty($_GET['ipp'])) ? $_GET['ipp']:$this->default_ipp;

	}



	function paginate()

	{

		if(!isset($this->default_ipp)) $this->default_ipp=100;

		

		if($_GET['ipp'] == 'All')

		{

			$this->num_pages = 1;

		}

		else

		{

			if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;

			$this->num_pages = ceil($this->items_total/$this->items_per_page);

		}

		

		$this->current_page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1 ; // must be numeric > 0

		$prev_page = $this->current_page-1;

		$next_page = $this->current_page+1;

		

		if($_GET)

		{

			$args = explode("&",$_SERVER['QUERY_STRING']);

			foreach($args as $arg)

			{

				$keyval = explode("=",$arg);

				if($keyval[0] != "page" And $keyval[0] != "ipp") $this->querystring .= "&" . $arg;

			}

		}



		if($_POST)

		{

			foreach($_POST as $key=>$val)

			{

				if($key != "page" And $key != "ipp") $this->querystring .= "&$key=$val";

			}

		}

		

		if($this->num_pages > 1)

		{

			$this->return = ($this->current_page > 1 And $this->items_total >= 10) ? "<div class='row'><div class='col-sm-7'><ul class='pagination'><li class='page-item'><a class=\"page-link\" href=\"$_SERVER[PHP_SELF]?page=$prev_page&ipp=$this->items_per_page$this->querystring\">Previous</a></li> ":"<div class='row'><div class='col-sm-7'><ul class='pagination'><li class='page-item'><a href=\"javascript:;\" class=\"page-link disabled\" tabindex=\"-1\">Previous</a></li> ";



			$this->start_range = $this->current_page - floor($this->mid_range/2);

			$this->end_range = $this->current_page + floor($this->mid_range/2);



			if($this->start_range <= 0)

			{

				$this->end_range += abs($this->start_range)+1;

				$this->start_range = 1;

			}

		

			if($this->end_range > $this->num_pages)

			{

				$this->start_range -= $this->end_range-$this->num_pages;

				$this->end_range = $this->num_pages;

			}

		

			$this->range = range($this->start_range,$this->end_range);



			for($i=1;$i<=$this->num_pages;$i++)

			{

				//if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= " ... ";

				if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= "";

				// loop through all pages. if first, last, or in range, display

				if($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))

				{

					$this->return .= ($i == $this->current_page And ($_GET['page'] != 'All')) ? "<li class='page-item active'><a title=\"Go to page $i of $this->num_pages\" class=\"page-link\" href=\"#\">$i</a></li> ":"<li class='page-item'><a class=\"page-link\" title=\"Go to page $i of $this->num_pages\" href=\"$_SERVER[PHP_SELF]?page=$i&ipp=$this->items_per_page$this->querystring\">$i</a></li> ";
					

				}

				//if($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1]) $this->return .= " ... ";

				if($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1]) $this->return .= "";

			}

			$this->return .= (($this->current_page < $this->num_pages And $this->items_total >= 10) And ($_GET['page'] != 'All') And $this->current_page > 0) ? "<li class='page-item'><a class=\"page-link\" href=\"$_SERVER[PHP_SELF]?page=$next_page&ipp=$this->items_per_page$this->querystring\">Next</a></li>\n":"<li class='page-item'><a href=\"javascript:;\" class=\"page-link disabled\" href=\"javascript:;\" tabindex=\"-1\">Next</a></li>\n";

			$this->return .= ($_GET['page'] == 'All') ? "<li class='page-item active'><a class=\"page-link\" hidden href=\"javascript:;\">All</a></li> \n":"<li class='page-item'><a class=\"page-link\" hidden href=\"$_SERVER[PHP_SELF]?page=1&ipp=All$this->querystring\">All</a></li></ul></div> \n";

		}

		else

		{

			for($i=1;$i<=$this->num_pages;$i++)

			{

				$this->return .= ($i == $this->current_page) ? "<div class='row'><div class='col-sm-7'><ul class='pagination'><li class='page-item active'><a class=\"page-link\" href=\"#\">$i</a></li> ":"<li class='page-item'><a class=\"page-link\" href=\"$_SERVER[PHP_SELF]?page=$i&ipp=$this->items_per_page$this->querystring\">$i</a></li> ";

			}

			$this->return .= "<li class='page-item'><a class=\"page-link\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All$this->querystring\">All</a></li></ul></div> \n";

		}

		$this->low = ($this->current_page <= 0) ? 0:($this->current_page-1) * $this->items_per_page;

		if($this->current_page <= 0) $this->items_per_page = 0;

		$this->limit = (isset($_GET['ipp']) && $_GET['ipp'] == 'All') ? "":" LIMIT $this->low,$this->items_per_page";

	}

	function display_items_per_page()

	{

		$items = '';

		if(!isset($_GET['ipp'])) $this->items_per_page = $this->default_ipp;

		foreach($this->ipp_array as $ipp_opt) $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n":"<option value=\"$ipp_opt\">$ipp_opt</option> \n";

		return "<div class='col-sm-5 float-sm-right'><div class='form-row mt-2 text-right'><div class='col'><span class=\"text-muted\">Rows:</span> <select class=\"border rounded text-muted\" onchange=\"window.location='$_SERVER[PHP_SELF]?page=1&ipp='+this[this.selectedIndex].value+'$this->querystring';return false\">$items</select></div>\n";

	}

	function display_jump_menu()

	{

		$option = '';

		for($i=1;$i<=$this->num_pages;$i++)

		{

			$option .= ($i==$this->current_page) ? "<option value=\"$i\" selected>$i</option>\n":"<option value=\"$i\">$i</option> \n";

		}

		return "<div class='col'><span class=\"text-muted\">Page:</span> <select class=\"border rounded text-muted\" onchange=\"window.location='$_SERVER[PHP_SELF]?page='+this[this.selectedIndex].value+'&ipp=$this->items_per_page$this->querystring';return false\">$option</select></div><div class='col'><strong class='text-danger'>Total: ".$this->items_total."</strong></div></div></div></div>\n";

	}

	function display_pages()

	{

		return $this->return;

	}

}

?>


<!doctype html>
<html lang="en-US" xmlns:fb="https://www.facebook.com/2008/fbml" xmlns:addthis="https://www.addthis.com/help/api-spec"  prefix="og: http://ogp.me/ns#" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Facturacion elec</title>
	
	<link rel="shortcut icon" href="https://demo.learncodeweb.com/favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script>
	  (adsbygoogle = window.adsbygoogle || []).push({
		google_ad_client: "ca-pub-6724419004010752",
		enable_page_level_ads: true
	  });
	</script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-131906273-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	  gtag('config', 'UA-131906273-1');
	</script>
</head>

<body>
	
	<div class="bg-light border-bottom shadow-sm sticky-top">
		<div class="container">
			<header class="blog-header py-1">
				<nav class="navbar navbar-expand-lg navbar-light bg-light"> <a class="navbar-brand text-muted p-0 m-0" href="https://learncodeweb.com"><img src='https://learncodeweb.com/wp-content/uploads/2019/01/logo.png' alt='LearnCodeWeb'></a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav mr-auto">
							<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-17" class="active nav-item"><a title="Home" href="https://learncodeweb.com/" class="nav-link">Home</a></li>
							<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-16" class="nav-item"><a title="Web Development" href="https://learncodeweb.com/learn/web-development/" class="nav-link">Comprobantes</a></li>
							<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-558" class="nav-item"><a title="PHP" href="https://learncodeweb.com/learn/php/" class="nav-link">Catalogo</a></li>
							<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-14" class="nav-item"><a title="Bootstrap" href="https://learncodeweb.com/learn/bootstrap-framework/" class="nav-link">Clientes</a></li>
							<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-559" class="nav-item"><a title="WordPress" href="https://learncodeweb.com/learn/wordpress/" class="nav-link">Anulaciones</a></li>
							<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-15" class="nav-item"><a title="Snippets" href="https://learncodeweb.com/learn/snippets/" class="nav-link">Configuracion</a></li>
						</ul>
						<form method="get" action="https://learncodeweb.com" class="form-inline my-2 my-lg-0">
							<div class="input-group input-group-md">
								<input type="text" class="form-control search-width" name="s" id="search" value="" placeholder="Search..." aria-label="Search">
								<div class="input-group-append">
									<button type="submit" class="btn btn-primary" id="searchBtn"><i class="fa fa-search"></i></button>
								</div>
							</div>
						</form>
					</div>
				</nav>
			</header>
		</div> <!--/.container-->
	</div>
	
	
	
	<div class="container">
		<h1><a href="https://learncodeweb.com/php/php-pagination-class-with-bootstrap-4/">Facturacion Electronica </a></h1>
    	<hr>
		<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-inline">
			<select name="tb1" onchange="submit()" class="form-control">
				<option>Por favor selecione un documento </option>
				<?php
					
					$q="SELECT td from notadets  GROUP BY td ";

                        $Continentqry = mysqli_query($con, $q);

					while($crow = $Continentqry->fetch_array()) {
						echo "<option";
						if(isset($_REQUEST['tb1']) and $_REQUEST['tb1']==$crow['td']) echo ' selected="selected"';
						echo ">{$crow['td']}</option>\n";
					}
				?>
			</select>
		</form>
		<hr>
		<?php
		if(isset($_REQUEST['tb1'])) {
			$condition		=	"";
			if(isset($_GET['tb1']) and $_GET['tb1']!="")
			{
				$condition		.=	"  td ='".$_GET['tb1']."'";
			}
			
			//Main query
			$pages = new Paginator;
			$pages->default_ipp = 15;
			$q = ("SELECT * FROM notadets WHERE  ".$condition."");
		
			$sql_forms = mysqli_query($con, $q);


			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();	
			$q = ("SELECT * FROM notadets WHERE  ".$condition." ORDER BY fecha,serie,numero ASC ".$pages->limit."");
			$result	= mysqli_query($con, $q);	
		}
		?>
		<div class="clearfix"></div>
		
		<div class="row marginTop">
			<div class="col-sm-12 paddingLeft pagerfwt">
				<?php if($pages->items_total > 0) { ?>
					<?php echo $pages->display_pages();?>
					<?php echo $pages->display_items_per_page();?>
					<?php echo $pages->display_jump_menu(); ?>
				<?php }?>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="clearfix"></div>
		
		<table class="table table-responsive table-bordered table-striped">
			<thead>
				<tr>
					<th>Id#</th>
					<th>Serie</th>
					<th>Numero</th>
					<th>Ruc</th>
					<th>Descripcion</th>
					<th>Importe</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				if($pages->items_total>0){
					$n  =   1;
					while($val  =   $result->fetch_assoc()){ 
				?>
				<tr>
					<td><?php echo $n++; ?></td>
					<td><?php echo mb_strtoupper($val['serie']); ?></td>
					<td><?php echo mb_strtoupper($val['numero']); ?></td>
					<td><?php echo mb_strtoupper($val['ruc']); ?></td>
					<td></td>
					
					<td><?php echo mb_strtoupper($val['importe']); ?></td>

					<td>
                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="delete_master.php?std_id=<?php echo $val['id']; ?>" class="btn  btn-sm btn-primary">Imprimir </a>
                               </div>
                            
                            </div>
                         </td>
                         <td>   
                            <div class="row">
                                <div class="col-sm-12">
                            
                                    <a href="update.php?std_id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary">Xml</a>
                                </div>
                            </div>
                          </td>

                        <td>   
                            <div class="row">
                                <div class="col-sm-12">
                            
                                    <a href="update.php?std_id=<?php echo $val['id']; ?>" class="btn  btn-sm btn-primary">Cdr</a>
                                </div>
                            </div>
                          </td>
  
                        <td>   
                            <div class="row">
                                <div class="col-sm-12">
                            
                                    <a href="update.php?std_id=<?php echo $val['id']; ?>" class="btn  btn-sm btn-primary">Estado Sunat </a>
                                </div>
                            </div>
                          </td>


                        </td>
				</tr>
				<?php 
					}
				}else{?>
				<tr>
					<td colspan="6" align="center"><strong>No Record(s) Found!</strong></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		
		<div class="clearfix"></div>
		
		<div class="row marginTop">
			<div class="col-sm-12 paddingLeft pagerfwt">
				<?php if($pages->items_total > 0) { ?>
					<?php echo $pages->display_pages();?>
					<?php echo $pages->display_items_per_page();?>
					<?php echo $pages->display_jump_menu(); ?>
				<?php }?>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="clearfix"></div>



		 <div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>
    <div class="outer-scontainer">
      <h3>Importa informacion </h3>
    	
        <div class="row">
            <form class="form-horizontal" action="" method="post"
                name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                <div class="input-row">
                    <label class="col-md-4 control-label"> Seleccione archivo
                        </label> <input type="file" name="file"
                        id="file" accept=".csv">
                    <button type="submit" id="submit" name="import"
                        class="btn-submit">Importar</button>
                    <br />

                </div>

            </form>

        </div>
        
    </div> <!--/.container-->

   
    
   

       
	<div class="container">
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- demo left sidebar -->
		<ins class="adsbygoogle"
			 style="display:block"
			 data-ad-client="ca-pub-6724419004010752"
			 data-ad-slot="7706376079"
			 data-ad-format="auto"
			 data-full-width-responsive="true"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>
	</div>


	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    


</body>
</html>



<?php

//importar informacion

if (isset($_POST["import"])) {
    
    $fileName = $_FILES["file"]["tmp_name"];
    
    if ($_FILES["file"]["size"] > 0) {
        
        $file = fopen($fileName, "r");

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {

        	$csvDate = $column[1] ;
        	$csvDate = strtotime($csvDate);
			$newCSVDate = date("Y-m-d",$csvDate);


			

            $sqlInsert = "INSERT into datacab (td,fecha,serie,numero,importe)
                   values ('" . $column[0] . "',
                   '" . $newCSVDate  . "',
                   '" . $column[2] . "',
                   '" . $column[3] . "',
                   '" . $column[4] . "'
                   )";


            $result = mysqli_query($con, $sqlInsert);
            
            if (! empty($result)) {
                $type = "success";
                $message = "CSV Data Imported into the Database";
            } else {
                $type = "error";
                $message =  mysqli_error( $con ) ;

            }
        }
    }
}


//procesar  informacion

if (isset($_POST["procesar"])) {
    
    $fileName = $_FILES["file"]["tmp_name"];
    
    if ($_FILES["file"]["size"] > 0) {
        
        $file = fopen($fileName, "r");

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {

        	$csvDate = $column[1] ;
        	$csvDate = strtotime($csvDate);
			$newCSVDate = date("Y-m-d",$csvDate);
			
            $sqlSelect = "SELECT * FROM  notadets where fecha = '$fec_1' ";
                   
            $result = mysqli_query($con, $sqlInsert);
            
            if (! empty($result)) {
                $type = "success";
                $message = "CSV Data Imported into the Database";
            } else {
                $type = "error";
                $message =  mysqli_error( $con ) ;

            }
        }
    }
}

?>

