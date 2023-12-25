pipeline {
    agent any
    tools {
        jdk 'jdk17'
        nodejs 'node16'
    }
    environment {
        SCANNER_HOME = tool 'sonar-scanner'
    }
    stages{
        stage("Cleaning"){
            steps{
                cleanWs()
            }
        }
        stage("Code clone"){
            steps{
                echo "code cloning"
                git url:"https://github.com/manjurulhasan/rupbot.git", branch:"main "
            }
        }
        stage("Sonarqube Analysis") {
            steps {
                // withSonarQubeEnv('sonar-server') {
                //     sh '''$SCANNER_HOME/bin/sonar-scanner -Dsonar.projectName=rupbot \
                //     -Dsonar.projectKey=rupbot'''
                // }
            }
        }
        stage("Build"){
            steps{
                echo "code Building"
                
            }
        }
        stage("Push To Docker"){
            steps{
                echo "Push to docker"
            }
        }
        stage("Deploy"){
            steps{
                echo "Deploying in server"
               
            }
        }
    }
}
