#! /bin/bash
echo "This is used to generate php code documentation via phpDocumentor."
echo "phpDocumentor must be installed to run this script."
echo "phpDocumentor have the following styles:\n clean and responsive-twig. \n or build your own using checkstyle."
read -p "Enter the template name you wish to use (default is responsive-twig):" template
if [ -z "$template" ]
  then
    template="responsive-twig"
fi
rm -rf "$(pwd)-doc"
phpdoc -d $(pwd) -t "$(pwd)-doc" -i app/plugins/ --template="$template"
echo "Code documentation is located in $(pwd)-doc."
