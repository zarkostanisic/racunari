<?php
      /**
  * Pagination
  */
  function pagination($table_name, $condition, $suffix, $address){
    include('konekcija.inc');
    $return_array = array();
    // find out how many rows are in the table
    $sql = "SELECT COUNT(*) FROM $table_name $condition";

    $result = mysql_query($sql, $konekcija) or trigger_error("SQL", E_USER_ERROR);
    $r = mysql_fetch_row($result);
    $numrows = $r[0];
    // number of rows to show per page
    $rowsperpage = 6;
    $return_array['rowsperpage'] = $rowsperpage;
    // find out total pages
    $totalpages = ceil($numrows / $rowsperpage);

    // get the current page or set a default
    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
       // cast var as int
       $page = (int) $_GET['page'];
    } else {
       // default page num
       $page = 1;
    } // end if

    // if current page is greater than total pages...
    if ($page > $totalpages) {
       // set current page to last page
       $page = $totalpages;
    } // end if
    // if current page is less than first page...
    if ($page < 1) {
       // set current page to first page
       $page = 1;
    } // end if

    // the offset of the list, based on current page
    $offset = ($page - 1) * $rowsperpage;
    $return_array['offset'] = $offset;
    /******  build the pagination links ******/
    // range of num links to show
    $range = 3;

  // if not on page 1, don't show back links
    $pagination = "<div class='pagination'>";
    if ($page > 1) {
       // show << link to go back to page 1
       $pagination .=  " <a href='$address?page=1$suffix'><<</a> ";
       // get previous page num
       $prevpage = $page - 1;
       // show < link to go back to 1 page
       $pagination .=  " <a href='$address?page=$prevpage$suffix'><</a> ";
    } // end if

    // loop to show links to range of pages around current page
    for ($x = ($page - $range); $x < (($page + $range) + 1); $x++) {
       // if it's a valid page number...
       if (($x > 0) && ($x <= $totalpages)) {
        // if we're on current page...
        if ($x == $page) {
         // 'highlight' it but don't make a link
         $pagination .=  " <span class='current'>$x</span> ";
        // if not current page...
        } else {
         // make it a link
         $pagination .=  " <a href='$address?page=$x$suffix'>$x</a> ";
        } // end else
       } // end if
    } // end for

    // if not on last page, show forward and last page links
    if ($page != $totalpages) {
       // get next page
       $nextpage = $page + 1;
      // echo forward link for next page
       $pagination .=  " <a href='$address?page=$nextpage$suffix'>></a> ";
       // echo forward link for lastpage
       $pagination .=  " <a href='$address?page=$totalpages$suffix'>>></a> ";
    } // end if
    $pagination .=  "</div>";
    $return_array['pagination'] = $pagination;
    /****** end build pagination links ******/
    $return_array['page'] = $page;
    return $return_array;

    mysql_close($konekcija);
  }
  /*
  *End of pagination
  */
?>
