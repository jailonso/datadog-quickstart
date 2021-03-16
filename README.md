# datadog-quickstart

The goal of this repo is to show how easy is the setup of Datadog.

1- Deploy Datadog following Datadog in-app instructions using attached values.yaml
```
helm install datadog datadog/datadog -f values.yaml 
```
2- Deploy Guestbook app https://cloud.google.com/kubernetes-engine/docs/tutorials/guestbook
```
kubectl apply -f frontend/
kubectl apply -f redis-master/
```
3- Visibility: container map, container orchestration, network map, service map

4- Troubleshooting: application is not working, trace should tell why
```
kubectl apply -f redis-slave/
```
5- Proactivity: create synthetic test

6- upgrade app
```
kubectl set image deployment/frontend php-redis=jailonso/php-redis:v3 --record
```
7- Confirmed synthetic test is working

8- Check custom metriuc guestbook revenue
