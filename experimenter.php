<!doctype html>
<html>
<head>
    <title>IAT Experimenter Page</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" href="core/css/overcast/jquery-ui-1.8.18.custom.css" rel="stylesheet" />
    <link type="text/css" href="core/css/experimenter.css" rel="stylesheet" />
    <script type="text/javascript" src="core/js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="core/js/jquery-ui-1.8.18.custom.min.js"></script>
    <script type="text/javascript" src="core/js/jquery-cookie.js"></script>
    <script type="text/javascript" src="core/js/experimenter.js"></script>
    <script type="text/javascript">
        initExperimenter();
    </script>

    <script src="angular.min.js"></script>

    <script>
        var app = angular.module('app', []);
        app.controller('MainController', function($scope, $http){
            $scope.initializeSurveys = function(){
                $scope.surveyOrIat = "Survey"
                $scope.newOrEdit = "new";
                $scope.surveyTemplate = $scope.getNewSurveyTemplate();
                $http.get("savedSurveys.json")
                    .then(function (response) {

                        $scope.surveys = (response.data);
                        if (!("surveys" in $scope.surveys))
                            $scope.surveys.surveys = {};

                    });
                $scope.sixty = [];
                for (var i = 0; i < 61; i++)
                    $scope.sixty.push(i)
                $scope.surveyStatus = "new";

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
                    keywords: "",
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


            }

            $scope.disableSurveys = function(){
                $scope.surveys.active = 0;
            }

            $scope.setActive = function(survey) {
                $scope.surveys["active"] = survey.id;
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
    </script>



</head>

<body ng-app="app" ng-controller="MainController">




    <div id="alert-window"></div>
    <div class="exp-header ui-widget-header">
        <div class="exp-header-active-label">Active:</div>
        <div class="exp-header-active">None</div>
    </div>
    <div >
        Choose One:
        <button  ng-click="surveyOrIat='IAT'">IAT</button>
        <button ng-click="surveyOrIat='Survey'">Survey</button>


    </div>



    <div  ng-show="surveyOrIat=='IAT'"     class="IATNotSurvey">
        <div class='selector-frame'>
            <div class='selector-label ui-corner-top ui-widget-content'>IAT Templates</div>
            <div class='active-selector ui-corner-tr ui-corner-bottom ui-widget-content'>
                <div class="template-item ui-corner-tr" id="create-new" name="create-new" onClick="loadCreateForm()">
                    <span class="template-item-label">[New Template]</span>
                </div>
            </div>
            <div class='selector-button-list'>
                <input type="button" id="set-active" name="set-active" value="Set Active">
                <!-- <input type="button" id="view-stats" name="view-stats"onclick="viewStats()" value="View Statistics" disabled="disabled"> -->
            </div>
        </div>
        <div id="exp-content">
        </div>
    </div>
    <div ng-show="surveyOrIat=='Survey'">
        <button  ng-click="newOrEdit='new'">New Survey</button>
        <button ng-click="newOrEdit='edit'">Edit Surveys</button>
        <button ng-click="disableSurveys()">Disable Survey Functionality</button>
        <button ng-click="saveChangesToSurveys()">Save Changes</button>
        <p style="font-size: x-small">
            to enable survey functionality, navigate to edit surveys, and select a survey to set as active
        </p>

        <div ng-show="newOrEdit=='new'">

            <h2>Create a New Survey</h2>
            <p>Enter Title:</p>
            <textarea ng-model="surveyTemplate.title" cols="50" rows="1"></textarea>
            <p>Enter Instructions:</p>
            <textarea ng-model="surveyTemplate.instructions" cols="50" rows="10"></textarea>
            <p>Enter Keywords:</p>
            <textarea ng-model="surveyTemplate.keywords" cols="50" rows="10"></textarea>
            <p>Enter Paragraph:</p>
            <textarea  ng-model="surveyTemplate.paragraph" cols="50" rows="10"></textarea>
            <br><br>How many keywords are in the paragraph?
            <select ng-model="surveyTemplate.numberOfKeyWordsInParagraph">
                <option ng-repeat="number in sixty" >{{number}}</option>
            </select>
            <p>Select Time:</p>
            Minutes
            <select ng-model="surveyTemplate.time.minutes">
                <option ng-repeat="minute in sixty" >{{minute}}</option>
            </select>
            Seconds
            <select ng-model="surveyTemplate.time.seconds">
                <option ng-repeat="second in sixty" >{{second}}</option>
            </select>
            <button ng-click="addNewSurvey()">Add</button>



        </div>
        <div ng-show="newOrEdit=='edit'">
            <h2>Choose a Survey to Edit</h2>
            <ul>
                <li ng-repeat="survey in surveys.surveys" >
                    {{survey.title}}
                    <button ng-click="editSurvey(survey)">edit </button>
                    <button ng-click="removeSurvey(survey)">remove</button>
                    <button ng-click="setActive(survey)">set active</button>
                </li>
            </ul>

                <p>Enter Title (no spaces):</p>
                <textarea ng-model="surveyToEdit.title" cols="50" rows="1"></textarea>

                <p>Enter Instructions:</p>
                <textarea ng-model="surveyToEdit.instructions" cols="50" rows="10"></textarea>

                <p>Enter Keywords:</p>
                <textarea ng-model="surveyToEdit.keywords" cols="50" rows="10"></textarea>

                <p>Enter Paragraph:</p>
                <textarea  ng-model="surveyToEdit.paragraph" cols="50" rows="10"></textarea>

                <br><br>How many keywords are in the paragraph?
                <select ng-model="surveyToEdit.numberOfKeyWordsInParagraph">
                    <option ng-repeat="number in sixty" >{{number}}</option>
                </select>

                <p>Select Time:</p>
                Minutes
                <select ng-model="surveyToEdit.time.minutes">
                    <option ng-repeat="minute in sixty" >{{minute}}</option>
                </select>
                Seconds
                <select ng-model="surveyToEdit.time.seconds">
                    <option ng-repeat="second in sixty" >{{second}}</option>
                </select>



        </div>

    </div>


    </div>
</body>