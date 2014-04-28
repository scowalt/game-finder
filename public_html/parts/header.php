<?php
/*********************************************
* Given a page's name, return the appropriate
* doctype and header tags for webpage creation
*********************************************/
function head($type) {
  //Assign generic doctype and meta tags
  $ret = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">".
         "<html xmlns=\"http://www.w3.org/1999/xhtml\">".
         "<head>".
         "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
         
  //Append the title tag and related file links (js/css)
  if($type == "index") {
    $ret .= "<title>GameFinder</title>";
    $ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/banner.css\">";
    $ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/content.css\">";
    $ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/index.css\">";
  }elseif($type == "search") {
    $ret .= "<title>GameFinder Search</title>";
    $ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/banner.css\">";
    $ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/content.css\">";
    $ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/search.css\">";
  }else {
    $ret .= "<title>GameFinder</title>";
    $ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/banner.css\">";
    $ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/content.css\">";
  }
  
  //Finish the return value
  $ret .= "</head>";
  
  return $ret;
}
?>