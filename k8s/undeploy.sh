#!/bin/bash

echo "=========== deleting... ==========="

kubectl delete -n default deployment rupbot-nginx-deployment
kubectl delete -n default service rupbot-nginx-service
kubectl delete -n default configmap rupbot-nginx-config

kubectl delete -n default service rupbot-service
kubectl delete -n default deployment rupbot-deployment
kubectl delete -n default persistentvolumeclaim rupbot-pvc

echo "=========== delete success... ==========="