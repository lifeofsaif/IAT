<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>
<head>
    <title>Online IAT</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" href="core/css/overcast/jquery-ui-1.8.18.custom.css" rel="stylesheet" />
    <style type="text/css"> @import "core/css/iat.css";</style>
    <script type="text/javascript" src="core/js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="core/js/jquery-ui-1.8.18.custom.min.js"></script>
    <script type="text/javascript" src="core/js/IAT.js"></script>
    <script type="text/javascript">
        initialize();
    </script>
    <script src="angular.min.js"></script>
    <script>
        var app = angular.module('app', []);
        app.controller('MainController', function($scope, $compile){
            $scope.showSurvey = false;
            $scope.lorem = "lorem ipsum hello world"

            $scope.loadSurvey = function(){
                console.log("loading survey")
                $("#instructions").html(
                    $compile(
                        "<div> " +

                            "beaner" +


                        "</div>"
                    )($scope)
                );
            }
        });
    </script>

</head>

<body ng-app='app' ng-controller="MainController">


    <div id="instructions">

        <label for="subID">Subject ID:</label>
        <input type="text" id="subID" name="subID"/>
        <br><br>
        <div> Please enter a unique subject identifier; No more than 30 characters, alphanumeric only. </div>
        <br>

        <input ng-show="!showSurvey" type="submit" value="Submit Subject ID" onclick='loadInstructions("one");'>

        <input ng-show="showSurvey" type="submit" value="Submit Subject ID" ng-click="loadSurvey()">


    </div>



</body>