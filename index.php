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
        surveyData = 5;
    </script>

    <script src="angular.min.js?version=1"></script>
    <script>
        var app = angular.module('app', []);
        app.controller('MainController', function($scope, $compile, $http, $interval){
            $scope.showSurvey = false;



            $scope.updateTime = function (time) {
                $scope.minutes = Math.floor(time/60);
                $scope.seconds = time%60;
                if ($scope.seconds < 10)
                    $scope.seconds = "0" + $scope.seconds;
            }


            $scope.startTimer = function(){
                $scope.timerStarted = true;
                var count = $scope.time;
                $interval(function () {
                    $scope.updateTime(--count)
                    if(count==0){
                        $scope.timerStarted = false;
                    }
                }, 1000 , count)
            }

            $scope.saveSurveyData = function () {
                surveyData = $scope.wordsHighlighted + '/' + $scope.activeSurvey.numberOfKeyWordsInParagraph;
            }


            $scope.initializeSurvey = function(){
                $http.get("savedSurveys.json")
                    .then(function (response) {
                        $scope.surveys = (response.data);
                        if (!("surveys" in $scope.surveys))
                            $scope.surveys.surveys = {};

                        if(($scope.surveys.active==0)) {
                            $scope.showSurvey = false;

                        }
                        else {

                            $scope.activeSurvey = $scope.surveys.surveys[$scope.surveys.active]
                            $scope.showSurvey = true;
                            $scope.time = $scope.activeSurvey.time.seconds + $scope.activeSurvey.time.minutes*60;
                            $scope.updateTime($scope.time);
                        }
                    });
                $scope.startSurvey=false
            }

            $scope.initializeSurvey();
            $scope.highlight = function (index,word) {
                if($scope.timerStarted && word.highlighted=="true") {
                    $scope["myStyle" + index] = word.style;
                    word.highlighted=false;
                    $scope.wordsHighlighted++;
                }
            }


            $scope.loadSurveyInstructions = function(){
                $scope.movingOnwards = true;
                $scope.wordsHighlighted = 0;
                $("#instructions").html(
                    $compile(
                        "<div> " +

                            "<h2>instructions</h2>"+
                            "<p>{{activeSurvey.instructions}}</p>" +
                            "<button ng-hide='startSurvey' ng-click='loadSurvey()'>start the survey</button>" +
                            "<div ng-show='startSurvey'>" +
                                "<h2 >{{activeSurvey.title}}</h2>"+
                                "<p>" +
                                    "<span ng-repeat='word in activeSurvey.wordArray track by $index' ng-style='myStyle{{$index}}' ng-click='highlight($index,word)'>" +
                                        "{{word.word}} "+
                                    "</span>" +
                                "</p>" +
                                "<p>TIME REMAINING: {{minutes}}:{{seconds}}</p>"+
                                "<p>WORDS HIGHLIGHTED: {{wordsHighlighted}}</p>" +
                                "<button ng-show='!timerStarted' onclick='loadInstructions(\"one\")' ng-click='saveSurveyData()'>continue</button>"+
                            "</div>"+

                        "</div>"
                    )($scope)
                );
            }

            $scope.loadSurvey = function(){
                $scope.startSurvey=true;
                $scope.startTimer();
            }




        });
    </script>

</head>

<body ng-app='app' ng-controller="MainController">

    <div ng-hide="movingOnwards">
        <label for="subID">Subject ID:</label>
        <input type="text" id="subID" name="subID"/>
        <br><br>
    </div>
    <div id="instructions" >
        <div> Please enter a unique subject identifier; No more than 30 characters, alphanumeric only. </div>
        <br>
        <input ng-show="!showSurvey" type="submit" value="Submit Subject ID" onclick='loadInstructions("one");' ng-click="movingOnwards = true">
        <input ng-show="showSurvey" type="submit" value="Submit Subject ID" ng-click="loadSurveyInstructions()">
    </div>



</body>