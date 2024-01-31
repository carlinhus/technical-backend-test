#!/bin/zsh
session="Test Project"
#Session start detched
tmux new-session -d -s $session
#Rename first window
tmux rename-window -t $session:1 'dockers'
#Second window
tmux new-window -t $session:2 -n 'ZSH-GIT'
tmux send-keys -t $session:2 'docker-compose up -d' C-m
#Third window
tmux new-window -t $session:3 -n 'SSH'

#Return to window 1
window=1
tmux select-window -t ${session}:1
while ! docker exec -it test_php whoami &>/dev/null; do sleep 1; done
tmux send-keys 'make php' C-m
#Split window
tmux split-window
until docker exec -ti test_db psql postgresql://postgres_test_user:testing@127.0.0.1/test_db -c "\dt" 2>/dev/null; do sleep 1; done
tmux send-keys 'make db' C-m

tmux select-pane -t ${window}.1
tmux attach-session -t $session
