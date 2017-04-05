<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>
<head>
    <title>Online IAT</title>
    <link type="text/css" href="core/css/overcast/jquery-ui-1.8.18.custom.css" rel="stylesheet"/>
    <style type="text/css"> @import "core/css/iat.css";</style>
    <script type="text/javascript" src="core/js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="core/js/jquery-ui-1.8.18.custom.min.js"></script>
    <script type="text/javascript" src="core/js/IAT.js"></script>
    <script type="text/javascript">
        initialize();
    </script>

    <!-- AngularJS source, survey functionality source-->
    <script src="angular.min.js"></script>
    <script src="core/js/surveyNG.js?version=1"></script>


<body ng-app='app' ng-controller="MainController">

<div ng-hide="movingOnwards">
    <label for="subID">Subject ID:</label>
    <input type="text" id="subID" name="subID"/>
    <br><br>
</div>

<div id="instructions" >
    <div>Please enter your email address.</div>
    <br>
    <div>NOTE: Please use a device with a separate keyboard (e.g., laptop or desktop).  The IAT measures your response times, and touchscreens do not provide reliable results.</div>
    <br>
    <input ng-show="!showSurvey" type="submit" value="Submit Subject ID" onclick='loadInstructions("one");' ng-click="movingOnwards = true">
    <input ng-show="showSurvey" type="submit" value="Submit Subject ID" ng-click="loadSurveyInstructions()">
</div>

</body>