var app = angular.module('app', []);
app.controller('MainController', function ($scope, $compile, $http, $interval) {
    $scope.showSurvey = false;


    $scope.updateTime = function (time) {
        $scope.minutes = Math.floor(time / 60);
        $scope.seconds = time % 60;
        if ($scope.seconds < 10)
            $scope.seconds = "0" + $scope.seconds;
    }

    var timer;
    $scope.startTimer = function () {
        $scope.timerStarted = true;
        var count = $scope.time;
        timer = $interval(function () {
            $scope.updateTime(--count)
            if (count == 0) {
                cancelTimer(count);
            }
        }, 1000, count)
    }
    function cancelTimer(count){
        $interval.cancel(timer);
        $scope.timerStarted = false;
        $scope.time = $scope.time - count;
    }


    $scope.saveSurveyData = function () {
        surveyData = "Time Elapsed: " + $scope.time + "\n"
        surveyData = surveyData + "Words Highlighted: "+$scope.wordsHighlighted + '/' + $scope.activeSurvey.numberOfKeyWordsInParagraph;
    }


    $scope.initializeSurvey = function () {
        $http.get("savedSurveys.json")
            .then(function (response) {
                $scope.surveys = (response.data);
                if (!("surveys" in $scope.surveys))
                    $scope.surveys.surveys = {};

                if (($scope.surveys.active == 0)) {
                    $scope.showSurvey = false;

                }
                else {

                    $scope.activeSurvey = $scope.surveys.surveys[$scope.surveys.active]
                    $scope.showSurvey = true;
                    $scope.time = $scope.activeSurvey.time.seconds + $scope.activeSurvey.time.minutes * 60;
                    $scope.updateTime($scope.time);
                }
            });
        $scope.startSurvey = false
    }

    $scope.initializeSurvey();

    $scope.highlight = function (parentIndex, index, word) {
        if ($scope.timerStarted && word.highlighted == "false") {
            $scope["myStyle" + parentIndex + "child" + index] = word.style;
            word.highlighted = false;
            $scope.wordsHighlighted++;
            word.highlighted = true;
        }
        if($scope.wordsHighlighted == $scope.activeSurvey.numberOfKeyWordsInParagraph) {
            console.log("DOS OEMTHING");
            var count = parseInt($scope.seconds) + $scope.minutes*60
            cancelTimer(count);
        }
    }


    $scope.loadSurveyInstructions = function () {
        $scope.movingOnwards = true;
        $scope.wordsHighlighted = 0;
        $("#instructions").html(
            $compile(
                "<div> " +
                "<h2 >{{activeSurvey.instructionHeader}}</h2>" +
                "<p>{{activeSurvey.instructions}}</p>" +
                "<button ng-hide='startSurvey' ng-click='loadSurvey()'>start</button>" +
                "<div ng-show='startSurvey'>" +
                "<h2 >{{activeSurvey.title}}</h2>" +
                "<p style='cursor:default;'>" +
                "<span ng-repeat='key in activeSurvey.wordArray track by $index' >" +
                    "<span ng-repeat='word in key track by $index' ng-style='myStyle{{$parent.$index}}child{{$index}}' ng-click='highlight($parent.$index, $index, word)'>{{word.word}}</span> " +
                "</span>" +
                "</p>" +
                "<p>TIME REMAINING: {{minutes}}:{{seconds}}</p>" +
                "<p>WORDS HIGHLIGHTED: {{wordsHighlighted}}</p>" +
                "<button ng-show='!timerStarted' onclick='loadInstructions(\"one\")' ng-click='saveSurveyData()'>continue</button>" +
                "</div>" +
                "</div>"

            )($scope)
        );
    }

    $scope.loadSurvey = function () {
        $scope.startSurvey = true;
        $scope.startTimer();
    }


});