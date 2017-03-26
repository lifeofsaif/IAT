var app = angular.module('app', []);
app.controller('MainController', function($scope, $http){
    $scope.fuck = function(index){
        console.log("fuck" + index)
    }


    $scope.splitPeas = function(obj){
        var wordArray = obj.paragraph.split(/  */);
        obj.wordArray = []
        for(var i = 0; i < wordArray.length; i++){
            obj.wordArray.push(
                {
                    "word": wordArray[i],
                    "style":{
                        "background-color": "white"
                    },
                    "highlighted": "false"
                })
        }
        var highlighters;
        if(obj.keywords){
            highlighters = obj.keywords.split(" ");
            for(var i = 0; i < highlighters.length; i++){
                for(var j = 0; j < obj.wordArray.length; j++){
                    if(  simplify(highlighters[i]) == simplify(obj.wordArray[j].word)  ) {
                        obj.wordArray[j].style = {
                            "background-color": "yellow"
                        }
                        obj.wordArray[j].highlighted = true;
                    }
                }
            }
        }

    };

    $scope.keywords = "";


    function simplify(word){
        return word.toLowerCase().replace(/[^\w\s]|_/g, "").replace(/\s+/g, " ")
    }


    $scope.initializeSurveys = function(){
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

    function setActiveSurvey(){
        if(parseInt($scope.surveys.active) == 0){
            $scope.activeSurvey = "NONE"

        } else{
            $scope.activeSurvey = $scope.surveys.surveys[$scope.surveys.active].title;
        }
    }



    $scope.addNewSurvey = function () {
        $scope.surveyTemplate.id = $scope.surveys["count"] = parseInt($scope.surveys["count"]) + 1;
        $scope.surveys.surveys[$scope.surveys["count"]] = $scope.surveyTemplate;
        $scope.surveyTemplate = $scope.getNewSurveyTemplate();
    }

    $scope.getNewSurveyTemplate = function() {
        return  {
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

    $scope.removeSurvey = function(survey){
        if(survey.id == $scope.surveys.active) {

            $scope.surveys.active = 0;
        }
        $scope.surveyToEdit = null;
        delete $scope.surveys.surveys[survey.id];
        setActiveSurvey();


    }

    $scope.disableSurveys = function(){
        $scope.surveys.active = 0;
        setActiveSurvey();
    }

    $scope.setActive = function(survey) {
        $scope.surveys["active"] = survey.id;
        setActiveSurvey();
    }

    $scope.saveChangesToSurveys = function(){
        if(confirm('Save all changes to survey?')) {

            $.ajax({
                url: 'savejson.php',
                type: 'POST',
                data: $scope.surveys
            });

        }else alert('A wise decision!')

    }

    $scope.initializeSurveys();



});
