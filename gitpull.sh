#!/bin/bash

tempfile=/var/www/ssh-agent.test

#Check for an existing ssh-agent
if [ -e $tempfile ]
then
      echo "Examining old ssh-agent"
      . $tempfile
fi

#See if the agent is still working
ssh-add -l > /dev/null

#If it's not working yet, just start a new one.
if [ $? != 0 ]
then
      echo "Old ssh-agent is dead..creating new agent."

      #Create a new ssh-agent if needed
      ssh-agent -s > $tempfile
      . $tempfile

      #Add the key
      ssh-add
fi    

#Show the user which keys are being used.
ssh-add -l
git pull
