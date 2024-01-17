#!/bin/bash

echo "=========== deleting... ==========="

kubectl delete -n default deployment nginx-deployment
kubectl delete -n default service nginx-service
kubectl delete -n default configmap nginx-config

kubectl delete -n default service php-service
kubectl delete -n default deployment php-deployment
kubectl delete -n default persistentvolumeclaim php-pvc

echo "=========== delete success... ==========="