pipeline {
  agent any
  stages {
    stage('Static Code Analysis') {
      parallel {
        stage('PHP CPD') {
          steps {
            sh 'phpcpd --exclude vendor workspace'
          }
        }
        stage('Calculating LOC') {
          steps {
            sh 'phploc workspace'
          }
        }
        stage('PHP Code Sniffer') {
          steps {
            sh 'phpcs --warning-severity=6 --standard=PSR2 --extensions=php --ignore=*/migrations/* workspace/modules'
          }
        }
        stage('PHP Depend') {
          steps {
            sh 'pdepend --ignore=vendor --summary-xml=/tmp/summary.xml --jdepend-chart=/tmp/jdepend.svg --overview-pyramid=/tmp/pyramid.svg workspace'
          }
        }
        stage('PHP MD') {
          steps {
            sh 'phpmd workspace/public/index.php text static_analysis/md_rulesets/cleancode.xml,static_analysis/md_rulesets/naming.xml,static_analysis/md_rulesets/codesize.xml'
          }
        }
        stage('PHP Lint Test') {
          steps {
            sh 'php -l workspace'
          }
        }
      }
    }
    stage('PHP Unit Test Cases...') {
      steps {        
        sh '''cp dit-app.conf workspace/.env
cd workspace
chmod -R 777 storage/
chmod -R 777 bootstrap/
composer update
composer dumpautoload
php artisan migrate
php artisan cache:clear
php artisan config:clear
php artisan config:cache
vendor/bin/phpunit --log-junit build/logs/junit.xml'''
      }
    }
    stage('SonarQube Analysis') {
      environment {
        PROJECT_NAME = 'Silicus-PHP-Demo-CI'
        PROJECT_KEY = 'silicus-php-demo-ci'        
        SONAR_HOST_URL = 'http://silicus.eastus.cloudapp.azure.com:9000'
        PROJECT_SOURCE_ENCODING = 'UTF-8'
        PROJECT_LANGUAGE = 'php'
      }
      steps {
        sh 'chmod -R 777 workspace/build'             
        sh '''PROJECT_VERSION=1.0.$(date +%y)$(date +%j).$BUILD_NUMBER
/opt/sonar/bin/sonar-runner -Dsonar.projectName=$PROJECT_NAME \\
-Dsonar.projectKey=$PROJECT_KEY \\
-Dsonar.host.url=$SONAR_HOST_URL \\
-Dsonar.sourceEncoding=$PROJECT_SOURCE_ENCODING \\
-Dsonar.sources=$WORKSPACE \\
-Dsonar.language=$PROJECT_LANGUAGE \\
-Dsonar.projectVersion=$PROJECT_VERSION \\
-Dsonar.php.tests.reportPath=$WORKSPACE/workspace/build/logs/junit.xml \\
-Dsonar.php.coverage.reportPaths=$WORKSPACE/workspace/build/logs/clover.xml \\
-Dorg.sonar.plugins.jmeter.jtlpath==$WORKSPACE/workspace/build/jmeter.jtl \\
-Dsonar.exclusions="workspace/app/**, workspace/bootstrap/**, workspace/build/**, workspace/resources/**,workspace/config/**, workspace/database/**, workspace/modules/infrastructure/**,workspace/modules/user/**, workspace/public/**, workspace/routes/**, workspace/storage/**, workspace/tests/**, workspace/vendor/**"'''
      }
    }		
    stage('Selenium Test Cases...') {
      steps {	    
		sh 'chmod -R 777 workspace/selenium/'
        sh 'java -cp workspace/selenium/Restapi1/bin:workspace/selenium/Restapi1/lib/* org.testng.TestNG workspace/selenium/Restapi1/testng.xml'
        step([$class: 'Publisher', reportFilenamePattern: '**/test-output/testng-results.xml'])		
      }
    }
    stage('Jmeter Test Cases...') {
      steps {
        sh '/opt/apache-jmeter-4.0/bin/jmeter -Jjmeter.save.saveservice.output_format=xml -n -t JavaDevOps.jmx -l workspace/build/jmeter.jtl'
        perfReport 'workspace/build/jmeter.jtl'
      }
    }  	    
    stage('Delete Workspace') {
      parallel {
        stage('Delete Workspace') {
          steps {
            cleanWs(cleanWhenAborted: true, cleanWhenFailure: true, cleanWhenNotBuilt: true, cleanWhenUnstable: true, cleanWhenSuccess: true, cleanupMatrixParent: true, deleteDirs: true)
          }
        }
      }
    }
  }
  environment {
    EMAIL_ADDRESS = 'ajay.bhosale@silicus.com'     
  }
  post {
    success {
      mail(to: $EMAIL_ADDRESS, subject: "Success Pipeline..: ${currentBuild.fullDisplayName}", body: "Congratulations pipeline build successfully ${env.BUILD_URL}")

    }
    failure {
      mail(to: $EMAIL_ADDRESS, subject: "Failed Pipeline: ${currentBuild.fullDisplayName}", body: "Something is wrong with ${env.BUILD_URL}")

    }

  }
}