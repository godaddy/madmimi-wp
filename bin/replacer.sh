#! /bin/bash

function renameFile {
	sourceFile=$1
	find=$2
	replaceWith=$3
	saveChanges=$4
	targetFile=$(echo $sourceFile | sed "s/$find/$replaceWith/g")
	
	if [[ $sourceFile != $targetFile ]]; then
		echo "Renaming file '$sourceFile' to '$targetFile'"
		if [[ $saveChanges == '1' ]]; then
			mv "$sourceFile" "$targetFile"
			echo "[OK]"
		fi
	fi
}

function replacePHPComments {
	sourceFile=$1
	find=$2
	replaceWith=$3
	pattern=$4
	saveChanges=$5
	
	if [[ $sourceFile =~ $pattern ]]; then
		fileContentOrg="$(cat "$sourceFile")"
		fileContent="$(cat "$sourceFile")"
		fileContent=$(echo "$fileContent" |sed -e "s/\(#[^\r\n]*\)$find\([^\r\n]*\)/\1$replaceWith\2/g")
		fileContent=$(echo "$fileContent" |sed -e "s/\(\/\/[^\r\n]*\)$find\([^\r\n]*\)/\1$replaceWith\2/g")
		
		# multiline:
		fileContent=$(echo "$fileContent" |sed -e ':a;N;$!ba;s/\n/_the_new_line/g')
		fileContent=$(echo "$fileContent" |sed -e "s/\(\/\*.*\)$find\(.*\*\/\)/\1$replaceWith\2/g")
		fileContent=$(echo "$fileContent" |sed -e 's/_the_new_line/\n/g')
		
		if [[ $fileContent != $fileContentOrg ]]; then
			echo "[Replace in PHP comments] $sourceFile"
			if [[ $saveChanges == '1' ]]; then
				echo "$fileContent" >| $sourceFile
				echo "[OK]"
			fi
		fi
	fi
}

function replacePHPStrings {
	sourceFile=$1
	find=$2
	replaceWith=$3
	pattern=$4
	saveChanges=$5
	
	if [[ $sourceFile =~ $pattern ]]; then
		fileContentOrg="$(cat "$sourceFile")"
		fileContent="$(cat "$sourceFile")"
		fileContent=$(echo "$fileContent" |sed -e "s/\('[^']*\)$find/\1$replaceWith/g")
		fileContent=$(echo "$fileContent" |sed -e "s/\(\"[^\"]*\)$find/\1$replaceWith/g")
		
		if [[ $fileContent != $fileContentOrg ]]; then
			echo "[Replace in PHP strings] $sourceFile"
			if [[ $saveChanges == '1' ]]; then
				echo "$fileContent" >| $sourceFile
				echo "[OK]"
			fi
		fi
	fi
}


function replaceCSSSelectors {
	sourceFile=$1
	find=$2
	replaceWith=$3
	pattern=$4
	saveChanges=$5
	
	if [[ $sourceFile =~ $pattern ]]; then
		fileContentOrg="$(cat "$sourceFile")"
		fileContent="$(cat "$sourceFile")"
		
		fileContent=$(echo "$fileContent" |sed -e "s/\([\ \#\.\w\-\,\s\n\r\t:]*\)$find/\1$replaceWith/g")
		
		if [[ $fileContent != $fileContentOrg ]]; then
			echo "[Replace in CSS selectors $sourceFile"
			if [[ $saveChanges == '1' ]]; then
				echo "$fileContent" >| $sourceFile
				echo "[OK]"
			fi
		fi
	fi
}

function replacePHPClassNamesSelectors {
	sourceFile=$1
	find=$2
	replaceWith=$3
	pattern=$4
	saveChanges=$5
	
	if [[ $sourceFile =~ $pattern ]]; then
		fileContentOrg="$(cat "$sourceFile")"
		fileContent="$(cat "$sourceFile")"
		
		fileContent=$(echo "$fileContent" |sed -e "s/\(class[ \t\n]*[A-Za-z_]*\)$find/\1$replaceWith/g")
		
		if [[ $fileContent != $fileContentOrg ]]; then
			echo "[Replace in CSS selectors $sourceFile"
			if [[ $saveChanges == '1' ]]; then
				echo "$fileContent" >| $sourceFile
				echo "[OK]"
			fi
		fi
	fi
}

function replaceAllOccurrences {
	sourceFile=$1
	find=$2
	replaceWith=$3
	pattern=$4
	saveChanges=$5
	
	if [[ $sourceFile =~ $pattern ]]; then
		fileContentOrg="$(cat "$sourceFile")"
		fileContent="$(cat "$sourceFile")"
		fileContent=$(echo "$fileContent" |sed -e "s/$find/$replaceWith/g")
		
		if [[ $fileContent != $fileContentOrg ]]; then
			echo "Replacing all occurrences of '$find' with '$replaceWith' in $sourceFile"
			if [[ $saveChanges == '1' ]]; then
				echo "$fileContent" >| $sourceFile
				echo "[OK]"
			fi
			
		fi
	fi
}

if [ $# -eq 0 ]; then
	echo ">> replacer <<";
	echo "By marcin-lawrowski (https://github.com/marcin-lawrowski)";
	echo "";
	echo "Usage: $0 path [disable-dry-run]";
	echo "";
	echo "path              = Existing directory to perform strings replacements on";
	echo "[disable-dry-run] = Use 1 value if you want to disable dry run mode";
	echo "";
	exit 1;
fi

ALLOWED_FILES_MASK="(\.php)|(\.css)|(\.js)|(\.txt)|(\.md)|(\.pot)"
targetPath=$1
saveChanges=$2

if [ ! -d "$targetPath" ]; then
	echo "$targetPath directory does not exist"
	exit 1;
fi

if [[ $saveChanges != '1' ]]; then
	echo "Dry run mode in action"
else
	echo "Dry run mode is disabled, all changes will be saved"
fi

for sourceFile in $(find "$targetPath" -not \( -path "$targetPath/dev-lib/*" -o -path "$targetPath/.git/*" \)); do
	if [ -f "$sourceFile" ]; then
		replaceAllOccurrences $sourceFile "GoDaddy Email Marketing" "Mad Mimi Sign Up Forms" "$ALLOWED_FILES_MASK$" $saveChanges
		replaceAllOccurrences $sourceFile "gem" "mimi" "$ALLOWED_FILES_MASK$" $saveChanges
		replaceAllOccurrences $sourceFile "GEM" "Mad_Mimi" "$ALLOWED_FILES_MASK$" $saveChanges
		replaceAllOccurrences $sourceFile "wp-godaddy-email-marketing" "madmimi-wp" "$ALLOWED_FILES_MASK$" $saveChanges
		replaceAllOccurrences $sourceFile "godaddy" "madmimi" "$ALLOWED_FILES_MASK$" $saveChanges
		replaceAllOccurrences $sourceFile "GoDaddy" "Mad Mimi" "$ALLOWED_FILES_MASK$" $saveChanges
		
		renameFile $sourceFile "gem" "mimi" $saveChanges
		renameFile $sourceFile "godaddy" "madmimi" $saveChanges
	fi
done

echo "Done"