#!/bin/bash

echo "=========== APP deploying ==========="

kubectl apply -f ./pvc.yaml
kubectl apply -f ./app_service.yaml
kubectl apply -f ./app_deployment.yaml

echo "=========== Nginx deploying ==========="

kubectl apply -f ./nginx_configMap.yaml
kubectl apply -f ./nginx_service.yaml
kubectl apply -f ./nginx_deployment.yaml

echo "=========== APP is ready ==========="