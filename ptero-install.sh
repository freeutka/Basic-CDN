#!/bin/bash

clear

BOLD=$(tput bold)
RESET=$(tput sgr0)
CYAN=$(tput setaf 6)
WHITE=$(tput setaf 7)
GREY=$(tput setaf 8)

TITLE="DuckCDN"
WELCOME="Welcome! Thank you for choosing us."
MESSAGE="Your CDN is now installing..."
FOOTER="GitHub - https://github.com/freeutka/DuckCDN"

WIDTH=60
PADDING=2

print_line() {
    printf "${CYAN}+"
    for ((i=0; i<$WIDTH; i++)); do printf "-"; done
    printf "+\n"
}

print_centered() {
    local text="$1"
    local length=${#text}
    local padding=$(( (WIDTH - length) / 2 ))
    printf "${CYAN}|"
    printf "%*s" $padding ""
    printf "${WHITE}${BOLD}%s${RESET}" "$text"
    printf "%*s" $((WIDTH - padding - length)) ""
    printf "${CYAN}|\n"
}

print_empty() {
    printf "${CYAN}|%*s|\n" $WIDTH ""
}

print_line
print_empty
print_centered "$TITLE"
print_empty
print_centered "$WELCOME"
print_centered "$MESSAGE"
print_empty
print_centered "$FOOTER"
print_empty
print_line
echo
