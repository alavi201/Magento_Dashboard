
<?php

$refresh = 300;



$interval = 900;
$type = 'day';

if(isset($_GET["value"]))
$interval = $_GET['value'];

if(isset($_GET["type"]))
$type = @$_GET['type'];

if(isset($_GET["refresh"]))
$refresh = @$_GET['refresh']*60;


header("Refresh: ".$refresh."");

require_once("constants.php");
/////////////////SALES///////////////////////////////
$response = mysql_query("SELECT sum(grand_total) from sales_flat_order where status='complete'",$connection) or die(mysql_error());
$row=mysql_fetch_array($response);

////////////////ADMINS////////////////////////////////
$response2 = mysql_query("SELECT count(*) from admin_user",$connection) or die(mysql_error());
$row2=mysql_fetch_array($response2);

////////////////PRODUCTS////////////////////////////////
$response3 = mysql_query("SELECT count(*) from catalog_product_entity",$connection) or die(mysql_error());
$row3=mysql_fetch_array($response3);

////////////////$VAL PER SKU////////////////////////////////
$response4 = mysql_query("SELECT 
  (SELECT sum(grand_total) from sales_flat_order where status='complete') as sales, 
  (SELECT COUNT(sku) FROM catalog_product_entity ) as skucnt",$connection) or die(mysql_error());
$row4=mysql_fetch_array($response4);

$response5 = mysql_query("SELECT count(distinct product_id) from sales_flat_order_item group by order_id",$connection) or die(mysql_error());
$row5=mysql_fetch_array($response5);

$response6 = mysql_query("SELECT 
  (SELECT COUNT(product_id) FROM sales_flat_order_item ) as prodcnt, 
  (SELECT COUNT(*) FROM sales_flat_order ) as ordrcnt",$connection) or die(mysql_error());
$row6=mysql_fetch_array($response6);

$response7 = mysql_query("SELECT 
  (SELECT sum(grand_total) from sales_flat_order where status='complete') as sales, 
  (SELECT COUNT(*) FROM sales_flat_order ) as ordrcnt",$connection) or die(mysql_error());
$row7=mysql_fetch_array($response7);

$response8 = mysql_query("SELECT count(*) from sales_flat_order",$connection) or die(mysql_error());
$row8=mysql_fetch_array($response8);

$x = array();

$response9 = mysql_query("SELECT count(value) as value, value as country FROM `customer_address_entity_varchar` WHERE attribute_id =27 group by country",$connection) or die(mysql_error());

while ($row9 = mysql_fetch_array($response9))
$x[] =$row9; 

////////////////////TOTAL CUSTOMERS/////////////

$response10 = mysql_query("SELECT count(*) from customer_entity",$connection) or die(mysql_error());
$row10=mysql_fetch_array($response10);


//////////////////ORDERS PER CUSTOMER/////////////
$response11 = mysql_query("SELECT
  (SELECT COUNT(*) FROM customer_entity ) as cuscnt, 
  (SELECT COUNT(*) FROM sales_flat_order ) as ordrcnt",$connection) or die(mysql_error());

$row11=mysql_fetch_array($response11);


///////////////////////////////SALES GROWTH///////////////////////

$response12 = mysql_query("SELECT sum(grand_total) as num, day(created_at) as day_cr,monthname(created_at) as month_cr,year(created_at) as year_cr from sales_flat_order where created_at > NOW()- INTERVAL ".$interval." ".$type." and status='complete' group by ".$type."(created_at)",$connection) or die(mysql_error());
$x1 = array();

while($row12=mysql_fetch_array($response12))
$x1[] = $row12;

///////////////////////////ADMIN GROWTH//////////////////////////

$response13 = mysql_query("SELECT count(*) as num, day(created) as day_cr,monthname(created) as month_cr,year(created) as year_cr from admin_user where created > NOW()- INTERVAL ".$interval." ".$type." group by ".$type."(created)",$connection) or die(mysql_error());
$x2 = array();

while($row13=mysql_fetch_array($response13))
$x2[] = $row13;

////////////////////////PRODUCT GROWTH/////////////////////////

$response14 = mysql_query("SELECT count(*) as num, day(created_at) as day_cr,monthname(created_at) as month_cr,year(created_at) as year_cr from catalog_product_entity where created_at > NOW()- INTERVAL ".$interval." ".$type." group by ".$type."(created_at)",$connection) or die(mysql_error());
$x3 = array();

while($row14=mysql_fetch_array($response14))
$x3[] = $row14;

///////////////////////////AVERAGE DOLLAR VALUE PER SKU GROWTH///////

$response15 = mysql_query("SELECT sum(grand_total) as num, day(created_at) as day_cr_cust, monthname(created_at) as month_cr_cust, year(created_at) as year_cr_cust FROM sales_flat_order where created_at >NOW()- INTERVAL ".$interval." ".$type." and  status='complete' group by ".$type."(created_at)",$connection) or die(mysql_error());
$x4 = array();
$y = array();

while($row15=mysql_fetch_array($response15)){
$x4[] = $row15;

}

$response16 = mysql_query("SELECT COUNT(sku) as sku_num, day(created_at) as day_cr_or,monthname(created_at) as month_cr_or,year(created_at) as year_cr_or from catalog_product_entity where created_at > NOW()- INTERVAL ".$interval." ".$type." group by ".$type."(created_at)",$connection) or die(mysql_error());


while($row16=mysql_fetch_array($response16))
$y[] = $row16;

////////////////////////////////GROWTH UNIQUE PRODUCTS PER ORDER/////////////////
$response17 = mysql_query("SELECT count(distinct product_id) as num, order_id as oid, day(created_at) as day_cr,monthname(created_at) as month_cr,year(created_at) as year_cr from sales_flat_order_item where created_at > NOW()- INTERVAL ".$interval." ".$type." group by order_id, ".$type."(created_at)",$connection) or die(mysql_error());
$x5 = array();

while($row17=mysql_fetch_array($response17))
$x5[] = $row17;

//////////////////////////////////GROWTH AVERAGE ITEMS PER ORDER///////////////

$x6 = array();
$y2 = array();


$response18 = mysql_query("SELECT count(product_id) as product_num, day(created_at) as day_cr_cust, monthname(created_at) as month_cr_cust, year(created_at) as year_cr_cust FROM sales_flat_order_item where created_at > NOW()- INTERVAL ".$interval." ".$type." group by ".$type."(created_at)",$connection) or die(mysql_error());

while($row18=mysql_fetch_array($response18)){
$x6[] = $row18;
}

$response19 = mysql_query("SELECT COUNT(*) as order_num, day(created_at) as day_cr_or,monthname(created_at) as month_cr_or,year(created_at) as year_cr_or from sales_flat_order where created_at > NOW()- INTERVAL ".$interval." ".$type." group by ".$type."(created_at)",$connection) or die(mysql_error());

while($row19=mysql_fetch_array($response19)){
$y2[] = $row19;
//echo $row2[0];
}

//////////////////////////////////////GROWTH AVERAGE DOLLAR VALUE PER ORDER/////////

$x7 = array();
$y3 = array();


$response20 = mysql_query("SELECT sum(grand_total) as num, day(created_at) as day_cr_cust, monthname(created_at) as month_cr_cust, year(created_at) as year_cr_cust FROM sales_flat_order where created_at > NOW()- INTERVAL ".$interval." ".$type." and  status='complete' group by ".$type."(created_at)",$connection) or die(mysql_error());

while($row20=mysql_fetch_array($response20)){
$x7[] = $row20;
}

$response21 = mysql_query("SELECT COUNT(*) as order_num, day(created_at) as day_cr_or,monthname(created_at) as month_cr_or,year(created_at) as year_cr_or from sales_flat_order where created_at > NOW()- INTERVAL ".$interval." ".$type."  group by ".$type."(created_at)",$connection) or die(mysql_error());

while($row21=mysql_fetch_array($response21)){
$y3[] = $row21;
}


///////////////////////////////////GROWTH ORDERS///////////////////////////////
$response22 = mysql_query("SELECT count(*) as num, day(created_at) as day_cr,monthname(created_at) as month_cr,year(created_at) as year_cr from sales_flat_order where created_at > NOW()- INTERVAL ".$interval." ".$type."  group by ".$type."(created_at)",$connection) or die(mysql_error());
$x8 = array();

while($row22=mysql_fetch_array($response22))
$x8[] = $row22;

//////////////////////////////////GROWTH CUSTOMERS//////////////////////////////

$response23 = mysql_query("SELECT count(*) as num, day(created_at) as day_cr,monthname(created_at) as month_cr,year(created_at) as year_cr from customer_entity where created_at > NOW()- INTERVAL ".$interval." ".$type."  group by ".$type."(created_at)",$connection) or die(mysql_error());
$x9 = array();

while($row23=mysql_fetch_array($response23))
$x9[] = $row23;

/////////////////////////////////GROWTH AVERAGE ORDER PER CUSTOMER///////////////

$x10 = array();
$y4 = array();

$response24 = mysql_query("SELECT COUNT(*) as cust_num, day(created_at) as day_cr_cust, monthname(created_at) as month_cr_cust, year(created_at) as year_cr_cust FROM customer_entity where created_at > NOW()- INTERVAL ".$interval." ".$type."  group by ".$type."(created_at)",$connection) or die(mysql_error());

while($row24=mysql_fetch_array($response24)){
$x10[] = $row24;

$response25 = mysql_query("SELECT COUNT(*) as order_num, day(created_at) as day_cr_or,monthname(created_at) as month_cr_or,year(created_at) as year_cr_or from sales_flat_order where created_at > NOW()- INTERVAL ".$interval." ".$type."  group by ".$type."(created_at)",$connection) or die(mysql_error());

}
while($row25=mysql_fetch_array($response25)){
$y4[] = $row25;
//echo $row2[0];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
  <style>
  iframe:focus { 
    outline: none;
}
#left, #right{
      float:left;
}
iframe[seamless] { 
    display: block;
}
 
#parent{
     margin-left:auto;
     marigin-right:auto;
}

#form{
	margin-left:auto;
     marigin-right:auto;
     
}

  </style>
    <title>
      Chart
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	 
    <script src="http://code.jquery.com/jquery-1.9.1.js" type="text/javascript"></script>
    	<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>

	



    <script type="text/javascript">
        $(function() {
            $('#container').highcharts({
				chart: {
				type: 'bar'
				},
                title: {
            text: 'Total Sales'
        },
		tooltip: {            
            
                formatter: function () {
                    return '$'+this.y+' total sales';
                }
				},
        xAxis: {
            categories: ['Sales']
        },
        
                series: [{
            name: 'Sales',
            data: [<?php echo $row[0]?>]
        }]
            });
        });
    </script>
	<script type="text/javascript">
        $(function() {
            $('#container2').highcharts({
				chart: {
				type: 'bar'
				},
                title: {
            text: 'Total Admins'
        },
        xAxis: {
            categories: ['Admins']
        },
        
                series: [{
            name: 'Admins',
            data: [<?php echo $row2[0]?>]
        }]
            });
        });
    </script>
	<script type="text/javascript">
        $(function() {
            $('#container3').highcharts({
				chart: {
				type: 'bar'
				},
                title: {
            text: 'Total Products'
        },
        xAxis: {
            categories: ['Products']
        },
        
                series: [{
            name: 'Prodcucts',
            data: [<?php echo $row3[0]?>]
        }]
            });
        });
    </script>
	<script type="text/javascript">
        $(function() {
            $('#container4').highcharts({
				chart: {
				type: 'bar'
				},
                title: {
            text: 'Average $value per SKU'
        },
        xAxis: {
            categories: ['$value']
        },
        
                series: [{
            name: '$value',
            data: [<?php echo round($row4[0]/$row4[1],2)?>]
        }]
            });
        });
    </script>
	<script type="text/javascript">
        $(function() {
            $('#container5').highcharts({
				chart: {
				type: 'bar'
				},
                title: {
            text: 'Unique products per Order'
        },
        xAxis: {
            categories: ['Prodcuts']
        },
        
                series: [{
            name: 'Unique products',
            data: [<?php echo $row5[0]?>]
        }]
            });
        });
    </script>
	<script type="text/javascript">
        $(function() {
            $('#container6').highcharts({
				chart: {
				type: 'bar'
				},
                title: {
            text: 'Average Items per Order'
        },
        xAxis: {
            categories: ['Items']
        },
        
                series: [{
            name: 'Items per order',
            data: [<?php echo round($row6[0]/$row6[1],2)?>]
        }]
            });
        });
    </script>
	<script type="text/javascript">
        $(function() {
            $('#container7').highcharts({
				chart: {
				type: 'bar'
				},
                title: {
            text: 'Average $value per Order'
        },
        xAxis: {
            categories: ['$value']
        },
        
                series: [{
            name: '$value',
            data: [<?php echo round($row7[0]/$row7[1],2)?>]
        }]
            });
        });
    </script>
	
	<script type="text/javascript">
        $(function() {
            $('#container8').highcharts({
				chart: {
				type: 'bar'
				},
                title: {
            text: 'Total Orders'
        },
        xAxis: {
            categories: ['Orders']
        },
        
                series: [{
            name: 'Orders',
            data: [<?php echo $row8[0]?>]
        }]
            });
        });
    </script>

	<script type="text/javascript">
        $(function() {
            $('#container10').highcharts({
				chart: {
				type: 'bar'
				},
                title: {
            text: 'Total Customers'
        },
        xAxis: {
            categories: ['Customers']
        },
        
                series: [{
            name: 'Customers',
            data: [<?php echo $row10[0]?>]
        }]
            });
        });
    </script>
	
	<script type="text/javascript">
        $(function() {
            $('#container11').highcharts({
				chart: {
				type: 'bar'
				},
                title: {
            text: 'Average Orders per customer'
        },
        xAxis: {
            categories: ['Orders']
        },
        
                series: [{
            name: 'Orders',
            data: [<?php echo round($row11[1]/$row11[0],2)?>]
        }]
            });
        });
    </script>
	
	

	
	<script type="text/javascript">
	
	var jArray= <?php echo json_encode($x1 ); ?>;
//alert (jArray[0].num);
	
	var categr_month = [];
	var cnt=[];
	
	var type_disp = '<?php echo $type;?>';
	
	for(var i = 0; i<jArray.length;i++){
	if(type_disp == 'month')
	categr_month[i] = jArray[i].month_cr+","+jArray[i].year_cr;
	else if (type_disp == 'year') 
	categr_month[i] = jArray[i].year_cr;
	else if (type_disp = 'day')
	categr_month[i] = jArray[i].day_cr+","+jArray[i].month_cr+","+jArray[i].year_cr;
	
	cnt[i] = parseInt(jArray[i].num);
	}
        $(function() {
            $('#container12').highcharts({
				chart: {
				type: 'column'
				},
                title: {
            text: 'Revenue growth from the last <?php echo $interval." ".$type."(S)"?>'
        },
		yAxis: {            
            labels: {
                formatter: function () {
                    return '$' + this.axis.defaultLabelFormatter.call(this);
                }            
            }
        },
				tooltip: {            
            
                formatter: function () {
                    return 'Revenue for '+this.x+' was $' + this.y;
                }            
            
        },
        xAxis: {
            categories: categr_month
        },
        
                series: [{
            name: 'Revenue',
            data: cnt
        }]
            });
        });
    </script>
	
	<script type="text/javascript">
	
	var jArray1= <?php echo json_encode($x2 ); ?>;
//alert (jArray[0].num);
	
	var categr_month1 = [];
	var cnt1=[];
	
	var type_disp = '<?php echo $type;?>';
	
	for(var i = 0; i<jArray1.length;i++){
	if(type_disp == 'month')
	categr_month1[i] = jArray1[i].month_cr+","+jArray1[i].year_cr;
	else if (type_disp == 'year') 
	categr_month1[i] = jArray1[i].year_cr;
	else if (type_disp = 'day')
	categr_month1[i] = jArray1[i].day_cr+","+jArray1[i].month_cr+","+jArray1[i].year_cr;
	cnt1[i] = parseInt(jArray1[i].num);
	}
        $(function() {
            $('#container13').highcharts({
				chart: {
				type: 'column'
				},
                title: {
            text: 'Admin users growth from the last <?php echo $interval." ".$type."(S)"?>'
        },
		tooltip: {            
            
                formatter: function () {
                    return this.y+' new admins for '+this.x;
                }
				}
				,
        xAxis: {
            categories: categr_month1
        },
        
                series: [{
            name: 'Admins',
            data: cnt1
        }]
            });
        });
    </script>
	
	<script type="text/javascript">
	
	var jArray2= <?php echo json_encode($x3 ); ?>;
//alert (jArray[0].num);
	
	var categr_month2 = [];
	var cnt2=[];
	
	var type_disp = '<?php echo $type;?>';
	
	for(var i = 0; i<jArray.length;i++){
	if(type_disp == 'month')
	categr_month2[i] = jArray2[i].month_cr+","+jArray2[i].year_cr;
	else if (type_disp == 'year') 
	categr_month2[i] = jArray2[i].year_cr;
	else if (type_disp = 'day')
	categr_month2[i] = jArray2[i].day_cr+","+jArray2[i].month_cr+","+jArray2[i].year_cr;
	cnt2[i] = parseInt(jArray2[i].num);
	}
        $(function() {
            $('#container14').highcharts({
				chart: {
				type: 'column'
				},
                title: {
            text: 'Product growth from the last <?php echo $interval." ".$type."(S)"?>'
        },
		tooltip: {            
            
                formatter: function () {
                    return this.y+' new products for '+this.x;
                }
				}
				,
        xAxis: {
            categories: categr_month2
        },
        
                series: [{
            name: 'Products',
            data: cnt2
        }]
            });
        });
    </script>
	
	<script type="text/javascript">
	
	var jArray3= <?php echo json_encode($y ); ?>;
	var iArray= <?php echo json_encode($x4 ); ?>;
//alert (jArray[0].num);
	
	var categr_month3 = [];
	var cnt3=[];
	
	var type_disp = '<?php echo $type;?>';
	
	for(var i = 0; i<iArray.length;i++){
	
	if(type_disp == 'month')
	categr_month3[i] = iArray[i].month_cr_cust+","+iArray[i].year_cr_cust;
	else if (type_disp == 'year')
	categr_month3[i] = iArray[i].year_cr_cust;
	else if (type_disp = 'day')
	categr_month3[i] = iArray[i].day_cr_cust+","+iArray[i].month_cr_cust+","+iArray[i].year_cr_cust;
	
	
	cnt3[i] = parseFloat(iArray[i].num)/parseFloat(jArray3[i].sku_num);
	}
        $(function() {
            $('#container15').highcharts({
				chart: {
				type: 'column'
				},
                title: {
            text: 'Average dollervalue per sku growth from the last <?php echo $interval." ".$type."(S)"?>'
        },
		tooltip: {            
            
                formatter: function () {
                    return '$'+this.y.toFixed(2)+' average value per sku for'+this.x;
                }
				}
				,
        xAxis: {
            categories: categr_month3
        },
        
                series: [{
            name: 'Average $value per sku',
            data: cnt3
        }]
            });
        });
    </script>
	
	<script type="text/javascript">
	
	var jArray4= <?php echo json_encode($x5); ?>;
//alert (jArray[0].num);
	
	var categr_month4 = [];
	var cnt4=[];
	var type_disp = '<?php echo $type;?>';
	
	for(var i = 0; i<jArray.length;i++){
	if(type_disp == 'month')
	categr_month4[i] = jArray4[i].month_cr+","+jArray4[i].year_cr;
	else if (type_disp == 'year') 
	categr_month4[i] = jArray4[i].year_cr;
	else if (type_disp = 'day')
	categr_month4[i] = jArray4[i].day_cr+","+jArray4[i].month_cr+","+jArray4[i].year_cr;
	
	cnt4[i] = parseInt(jArray4[i].num);
	}
        $(function() {
            $('#container16').highcharts({
				chart: {
				type: 'column'
				},
                title: {
            text: 'Unique products per order growth from the last <?php echo $interval." ".$type."(S)"?>'
        },
		tooltip: {            
            
                formatter: function () {
                    return this.y+' unique products per order for '+this.x;
                }
				}
				,
        xAxis: {
            categories: categr_month4
        },
        
                series: [{
            name: 'Unique products per order',
            data: cnt4
        }]
            });
        });
    </script>
	
	<script type="text/javascript">
	
	var jArray5= <?php echo json_encode($y2 ); ?>;
	var iArray2= <?php echo json_encode($x6 ); ?>;
//alert (jArray[0].num);
	
	var categr_month5 = [];
	var cnt5=[];
	
	var type_disp = '<?php echo $type;?>';
	
	for(var i = 0; i<iArray2.length;i++){
	
	if(type_disp == 'month')
	categr_month5[i] = iArray2[i].month_cr_cust+","+iArray2[i].year_cr_cust;
	else if (type_disp == 'year')
	categr_month5[i] = iArray2[i].year_cr_cust;
	else if (type_disp = 'day')
	categr_month5[i] = iArray2[i].day_cr_cust+","+iArray2[i].month_cr_cust+","+iArray2[i].year_cr_cust;
	
	
	cnt5[i] = parseFloat(iArray2[i].product_num)/parseFloat(jArray5[i].order_num);
	}
        $(function() {
            $('#container17').highcharts({
				chart: {
				type: 'column'
				},
                title: {
            text: 'Product per Order growth from the last <?php echo $interval." ".$type."(S)"?>'
        },
		tooltip: {            
            
                formatter: function () {
                    return this.y.toFixed(2)+' average products per order for'+this.x;
                }
				}
				,
        xAxis: {
            categories: categr_month5
        },
        
                series: [{
            name: 'Average products per order',
            data: cnt5
        }]
            });
        });
    </script>
	
	<script type="text/javascript">
	
	var jArray6= <?php echo json_encode($y3 ); ?>;
	var iArray3= <?php echo json_encode($x7 ); ?>;
//alert (jArray[0].num);
	
	var categr_month6 = [];
	var cnt6=[];
	
	var type_disp = '<?php echo $type;?>';
	
	for(var i = 0; i<iArray3.length;i++){
	
	if(type_disp == 'month')
	categr_month6[i] = iArray3[i].month_cr_cust+","+iArray3[i].year_cr_cust;
	else if (type_disp == 'year')
	categr_month6[i] = iArray3[i].year_cr_cust;
	else if (type_disp = 'day')
	categr_month6[i] = iArray3[i].day_cr_cust+","+iArray3[i].month_cr_cust+","+iArray3[i].year_cr_cust;
	
	
	cnt6[i] = parseFloat(iArray3[i].num)/parseFloat(jArray6[i].order_num);
	}
        $(function() {
            $('#container18').highcharts({
				chart: {
				type: 'column'
				},
                title: {
            text: 'Average value per order growth from the last <?php echo $interval." ".$type."(S)"?>'
        },
		tooltip: {            
            
                formatter: function () {
                    return '$'+this.y.toFixed(2)+' average value per order for'+this.x;
                }
				}
				,
        xAxis: {
            categories: categr_month
        },
        
                series: [{
            name: '$value per order',
            data: cnt
        }]
            });
        });
    </script>

	<script type="text/javascript">
	
	var jArray7= <?php echo json_encode($x8 ); ?>;
//alert (jArray[0].num);
	
	var categr_month7 = [];
	var cnt7=[];
	
	var type_disp = '<?php echo $type;?>';
	
	for(var i = 0; i<jArray.length;i++){
	if(type_disp == 'month')
	categr_month7[i] = jArray7[i].month_cr+","+jArray7[i].year_cr;
	else if (type_disp == 'year') 
	categr_month7[i] = jArray7[i].year_cr;
	else if (type_disp = 'day')
	categr_month7[i] = jArray7[i].day_cr+","+jArray7[i].month_cr+","+jArray7[i].year_cr;
	
	cnt7[i] = parseInt(jArray7[i].num);
	}
        $(function() {
            $('#container19').highcharts({
				chart: {
				type: 'column'
				},
                title: {
            text: 'Orders growth from the last <?php echo $interval." ".$type."(S)"?>'
        },
		tooltip: {            
            
                formatter: function () {
                    return this.y+' new orders for '+this.x;
                }
				}
				,
        xAxis: {
            categories: categr_month7
        },
        
                series: [{
            name: 'Orders',
            data: cnt7
        }]
            });
        });
    </script>
	
	<script type="text/javascript">
	
	var jArray8= <?php echo json_encode($x9); ?>;
//alert (jArray[0].num);
	
	var categr_month8 = [];
	var cnt8=[];
	var type_disp = '<?php echo $type;?>';
	
	for(var i = 0; i<jArray8.length;i++){
	if(type_disp == 'month')
	categr_month8[i] = jArray8[i].month_cr+","+jArray8[i].year_cr;
	else if (type_disp == 'year') 
	categr_month8[i] = jArray8[i].year_cr;
	else if (type_disp = 'day')
	categr_month8[i] = jArray8[i].day_cr+","+jArray8[i].month_cr+","+jArray8[i].year_cr;
	
	cnt8[i] = parseInt(jArray8[i].num);
	}
        $(function() {
            $('#container20').highcharts({
				chart: {
				type: 'column'
				},
                title: {
            text: 'Customer growth from the last <?php echo $interval." ".$type."(S)"?>'
        },
		tooltip: {            
            
                formatter: function () {
                    return this.y+' new customers for '+this.x;
                }
				}
				,
        xAxis: {
            categories: categr_month8
        },
        
                series: [{
            name: 'Customers',
            data: cnt8
        }]
            });
        });
    </script>
	
	<script type="text/javascript">
	
	var jArray9= <?php echo json_encode($x10 ); ?>;
	var iArray4= <?php echo json_encode($y4 ); ?>;
//alert (jArray[0].num);
	
	var categr_month9 = [];
	var cnt9=[];
	
	var type_disp = '<?php echo $type;?>';
	
	for(var i = 0; i<jArray9.length;i++){
	
	if(type_disp == 'month')
	categr_month9[i] = jArray9[i].month_cr_cust+","+jArray9[i].year_cr_cust;
	else if (type_disp == 'year') 
	categr_month9[i] = jArray9[i].year_cr_cust;
	else if (type_disp = 'day')
	categr_month9[i] = jArray9[i].day_cr_cust+","+jArray9[i].month_cr_cust+","+jArray9[i].year_cr_cust;
	
	
	cnt9[i] = (parseInt(iArray4[i].order_num)/parseInt(jArray9[i].cust_num));
	}
        $(function() {
            $('#container21').highcharts({
				chart: {
				type: 'column'
				},
                title: {
            text: 'Orders per customer growth from the last <?php echo $interval." ".$type."(S)"?>'
        },
		tooltip: {            
            
                formatter: function () {
                    return this.y.toFixed(2)+' new orders per customer for '+this.x;
                }
				}
				,
        xAxis: {
            categories: categr_month9
        },
        
                series: [{
            name: 'Order per customer',
            data: cnt9
        }]
            });
        });
    </script>
  </head>
  <body>
    <div id="parent">
	
  <div id ="left">
  <form id="refresh" method="GET" align="left">
	Enter the refresh rate (minutes) : <input type="number" name="refresh" value="<?php echo $refresh/60;?>" required>
	<input type="hidden" name="value" value="<?php echo $interval;?>" >
	<input type="hidden" name="type" value="<?php echo $type;?>" >
	<input type="submit">
	</form>
    <div id="container" ></div>
	<div id="container2" ></div>
	<div id="container3" ></div>
	<div id="container4" ></div>
	<div id="container5" ></div>
	<div id="container6" ></div>
	<div id="container7" ></div>
	<div id="container8" ></div>
	<div id="container10" ></div>
	<div id="container11" ></div>
	<iframe src="map.php" width="600" height="500" frameBorder="0" seamless></iframe>
	</div>
	
	<form id="myform" method="GET" align="center">
	Enter the period: <input type="number" name="value" value="<?php echo $interval;?>" required>
	<select name="type">
		<option value="year">Years</option>
		<option value="month">Months</option>
		<option value="day">Days</option>
	<input type="hidden" name="refresh" value="<?php echo $refresh/60;?>" >
	</select>
	
	<input type="submit">
	</form>
	
	<div id ="right">
	
    <div id="container12" ></div>
	<div id="container13" ></div>
	<div id="container14" ></div>
	<div id="container15" ></div>
	<div id="container16" ></div>
	<div id="container17" ></div>
	<div id="container18" ></div>
	<div id="container19" ></div>
	<div id="container20" ></div>
	<div id="container21" ></div>
	<iframe src="growth_customer_country.php?value=<?php echo $interval;?>&type=<?php echo $type;?>" width="600" height="600" frameBorder="0" seamless></iframe>
	</div>
	</div>
  </body>
</html>
