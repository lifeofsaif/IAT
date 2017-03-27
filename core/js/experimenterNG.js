var app = angular.module('app', []);
app.controller('MainController', function ($scope, $http) {
    $scope.fuck = function (index) {
        console.log("fuck" + index)
    }


    $scope.splitPeas = function (obj) {

        //var wordArray = obj.paragraph.split(/\s*\b\s*/);
        if (obj.paragraph) {
            var wordArray = obj.paragraph.split(/  */);
            for (var i = 0; i < wordArray.length; i++) {
                wordArray[i] = wordArray[i].match(/[\w-']+|[^\w\s]+/g);
                for (j = 0; j < wordArray[i].length; j++) {
                    wordArray[i][j] = {
                        "word": wordArray[i][j]
                    }
                }
            }





            if (obj.keywords) {
                var keywords = obj.keywords.split(" ");
                for(var z = 0; z < keywords.length; z++) {
                    for (var i = 0; i < wordArray.length; i++) {
                        for (var j = 0; j < wordArray[i].length; j++) {
                            if( simplify(keywords[z]) == simplify(wordArray[i][j].word) ){
                                wordArray[i][j].style = {
                                    "background-color": "yellow"
                                }
                                wordArray[i][j].highlighted = "false";
                            }
                        }
                    }
                }

            }

            obj.wordArray = wordArray;

        } else {
            obj.wordArray = null;
        }
    };

    $scope.keywords = "";


    function simplify(word) {
        return word.toLowerCase();
    }


    $scope.initializeSurveys = function () {
        $scope.surveyOrIat = "IAT"
        $scope.newOrEdit = "new";
        $scope.surveyTemplate = $scope.getNewSurveyTemplate();
        $http.get("savedSurveys.json")
            .then(function (response) {

                $scope.surveys = (response.data);
                if (!("surveys" in $scope.surveys))
                    $scope.surveys.surveys = {};
                setActiveSurvey();
            });
        $scope.sixty = [];
        for (var i = 0; i < 61; i++)
            $scope.sixty.push(i)
        $scope.surveyStatus = "new";
    }

    function setActiveSurvey() {
        if (parseInt($scope.surveys.active) == 0) {
            $scope.activeSurvey = "NONE"

        } else {
            $scope.activeSurvey = $scope.surveys.surveys[$scope.surveys.active].title;
        }
    }


    $scope.addNewSurvey = function () {
        $scope.surveyTemplate.id = $scope.surveys["count"] = parseInt($scope.surveys["count"]) + 1;
        $scope.surveys.surveys[$scope.surveys["count"]] = $scope.surveyTemplate;
        $scope.surveyTemplate = $scope.getNewSurveyTemplate();
    }

    $scope.getNewSurveyTemplate = function () {
        return {
            title: "",
            instructions: "",
            numberOfKeywordsInParagraph: "",
            paragraph: "",
            time: {
                minutes: 0,
                seconds: 0
            },
            id: ""
        }
    }

    $scope.editSurvey = function (survey) {
        $scope.surveyToEdit = survey
    }

    $scope.removeSurvey = function (survey) {
        if (survey.id == $scope.surveys.active) {

            $scope.surveys.active = 0;
        }
        $scope.surveyToEdit = null;
        delete $scope.surveys.surveys[survey.id];
        setActiveSurvey();


    }

    $scope.disableSurveys = function () {
        $scope.surveys.active = 0;
        setActiveSurvey();
    }

    $scope.setActive = function (survey) {
        $scope.surveys["active"] = survey.id;
        setActiveSurvey();
    }

    $scope.saveChangesToSurveys = function () {
        if (confirm('Save all changes to survey?')) {

            $.ajax({
                url: 'savejson.php',
                type: 'POST',
                data: $scope.surveys
            });

        } else alert('A wise decision!')

    }

    $scope.initializeSurveys();


});
