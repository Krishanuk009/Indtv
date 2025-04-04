@echo off
set JAVA_HOME=C:\Program Files\Java\jdk-24
echo JAVA_HOME set to: %JAVA_HOME%
call flutter build apk --release
