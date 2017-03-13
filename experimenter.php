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
            initializeSurveys();
            function initializeSurveys() {
                $scope.selectedTab = "IAT"
                $scope.surveyTemplate = {
                    title: "",
                    instructions: "",
                    keywords: "",
                    paragraph: "",
                    time: {
                        minutes: 0,
                        seconds: 0
                    }
                };
                $scope.sixty = [];
                for (var i = 0; i < 61; i++)
                    $scope.sixty.push(i)
                $scope.surveyStatus = "new";
                $http.get("exampleToDelete.json")
                    .then(function (response) {
                        $scope.surveys = (response.data);
                    });
            }
            $scope.setTab = function(selection){
                $scope.selectedTab = selection
            }
            $scope.saveNewSurvey = function(){
                $.getJSON('exampleToDelete.json', function (data) {
                    data[$scope.surveyTemplate.title] = $scope.surveyTemplate;
                    $scope.surveys = data;
                    $.ajax({
                        url: 'savejson.php',
                        type: 'POST',
                        data: data
                    });
                })
            }
            $scope.saveAllEditedSurveys = function () {
                $.ajax({
                    url: 'savejson.php',
                    type: 'POST',
                    data: $scope.surveys
                });
            }
            $scope.newOrEditSurvey = function(selection){
                $scope.surveyStatus = selection;
            }
            $scope.selectToEditSurvey = function (survey) {
                $scope.surveyToEdit = $scope.surveys[survey.title];
            }

        });
    </script>

    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.css">


</head>

<body ng-app="app" ng-controller="MainController">



	<div id="alert-window"></div>
	<div class="exp-header ui-widget-header">
		<div class="exp-header-active-label">Active:</div>
		<div class="exp-header-active">None</div>
    </div>

    <div >
        Choose One:
        <button  ng-click="setTab('IAT')">IAT</button>
        <button ng-click="setTab('Survey')">Survey</button>
        <button ng-click="setTab('neither')">Neither</button>
    </div>



    <div ng-hide="selectedTab!='IAT'" class="IATNotSurvey">
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




            <!-- SURVEY FRONT END END -->

        </div>
        <div id="exp-content">
        </div>
    </div>
    <div ng-hide="selectedTab!='Survey'" class="SurveyNotIAT">


        <div >
            Choose One:
            <button  ng-click="newOrEditSurvey('new')">Create New Survey</button>
            <button ng-click="newOrEditSurvey('edit')">Edit/View Existing Surveys</button>
        </div>

        <div ng-hide="surveyStatus!='new'">
            <h2>Create a New Survey</h2>

            <p>Enter Title (no spaces):</p>
            <textarea ng-model="surveyTemplate.title" cols="50" rows="1"></textarea>

            <p>Enter Instructions:</p>
            <textarea ng-model="surveyTemplate.instructions" cols="50" rows="10"></textarea>

            <p>Enter Keywords:</p>
            <textarea ng-model="surveyTemplate.keywords" cols="50" rows="10"></textarea>

            <p>Enter Paragraph:</p>
            <textarea  ng-model="surveyTemplate.paragraph" cols="50" rows="10"></textarea>

            <p>Select Time</p>
            Minutes
            <select ng-model="surveyTemplate.time.minutes">
                <option ng-repeat="minute in sixty" >{{minute}}</option>
            </select>
            Seconds
            <select ng-model="surveyTemplate.time.seconds">
                <option ng-repeat="second in sixty" >{{second}}</option>
            </select>

            <button ng-click="saveNewSurvey()">Save</button>
        </div>
        <div ng-hide="surveyStatus!='edit'">


            <h2>Choose a Survey to Edit</h2>
            <ul>
                <li ng-repeat="survey in surveys" ng-click="selectToEditSurvey(survey)">{{survey.title}}</li>
            </ul>



            <p>Enter Title (no spaces):</p>
            <textarea ng-model="surveyToEdit.title" cols="50" rows="1"></textarea>

            <p>Enter Instructions:</p>
            <textarea ng-model="surveyToEdit.instructions" cols="50" rows="10"></textarea>

            <p>Enter Keywords:</p>
            <textarea ng-model="surveyToEdit.keywords" cols="50" rows="10"></textarea>

            <p>Enter Paragraph:</p>
            <textarea  ng-model="surveyToEdit.paragraph" cols="50" rows="10"></textarea>

            <p>Select Time</p>
            Minutes
            <select ng-model="surveyToEdit.time.minutes">
                <option ng-repeat="minute in sixty" >{{minute}}</option>
            </select>
            Seconds
            <select ng-model="surveyToEdit.time.seconds">
                <option ng-repeat="second in sixty" >{{second}}</option>
            </select>

            <button ng-click="saveAllEditedSurveys()">Save All Surveys</button>



        </div>


    </div>
    <div ng-hide="selectedTab!='neither'">

    </div>
</body>