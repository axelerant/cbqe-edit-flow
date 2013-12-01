#!/bin/bash

NEW_ABBR="CBQE_EF_"
NEW_BASE="cbqe-edit-flow"
NEW_CLASS="Custom_Bulkquick_Edit_Edit_Flow"
NEW_EXT="Edit Flow"
NEW_EXTENDS="Custom Bulk/Quick Edit"
NEW_EXTENDS_URL="http://wordpress.org/plugins/custom-bulkquick-edit/"
NEW_EXT_API="https://github.com/michael-cannon/${NEW_BASE}/blob/master/API.md"
NEW_EXT_BASE="edit-flow"
NEW_EXT_CHANGELOG="https://github.com/michael-cannon/${NEW_BASE}/blob/master/CHANGELOG.md"
NEW_EXT_TODO="https://github.com/michael-cannon/${NEW_BASE}/blob/master/TODO.md"
NEW_EXT_URL="http://wordpress.org/plugins/${NEW_EXT_BASE}/"
NEW_KB_PATH=""
NEW_NOTICE="_ef"
NEW_SITE="http://aihr.us/edit-flow-custom-bulkquick-edit/"
NEW_SLUG="cbqe_ef_"

NEW_EXT_SHORT=${NEW_EXT}
NEW_FILTER="${NEW_SLUG}"
NEW_SLUG_LONG="${NEW_SLUG}"
NEW_STORE_URL=${NEW_EXT_URL}
NEW_TAG=`echo ${NEW_EXT_BASE} | sed -e 's/-/ /g'`
NEW_TITLE="${NEW_EXT} for ${NEW_EXTENDS}"


OLD_ABBR="CBQEP_WPSEO_"
OLD_BASE="cbqep-wordpress-seo"
OLD_CLASS="Custom_Bulkquick_Edit_Premium_WordPress_Seo"
OLD_EXT="WordPress SEO by Yoast"
OLD_EXTENDS="Custom Bulk/Quick Edit Premium"
OLD_EXTENDS_URL="https://aihr.us/products/custom-bulkquick-edit-premium-wordpress-plugin/"
OLD_EXT_API="http://aihr.us/edit-flow-custom-bulkquick-edit-premium/api/"
OLD_EXT_BASE="wordpress-seo"
OLD_EXT_CHANGELOG="http://aihr.us/edit-flow-custom-bulkquick-edit-premium/changelog/"
OLD_EXT_SHORT="WordPress SEO"
OLD_EXT_TODO="http://aihr.us/edit-flow-custom-bulkquick-edit-premium/todo/"
OLD_EXT_URL="http://wordpress.org/plugins/wordpress-seo/"
OLD_KB_PATH="20112546-Custom-Bulk-Quick-Edit"
OLD_NOTICE="_ef"
OLD_SITE="http://aihr.us/wordpress-seo-custom-bulkquick-edit-premium/"
OLD_SLUG="cbqep_wpseo_"
OLD_STORE_URL="http://aihr.us/products/wordpress-seo-custom-bulkquick-edit-premium/"
OLD_TITLE="WordPress SEO by Yoast for Custom Bulk/Quick Edit Premium"

OLD_FILTER="${OLD_SLUG}"
OLD_SLUG_LONG="${OLD_SLUG}"
OLD_TAG=`echo ${OLD_EXT_BASE} | sed -e 's/-/ /g'`

echo
echo "Begin converting ${OLD_TITLE} to ${NEW_TITLE} plugin"

FILES=`find . -type f \( -name "*.md" -o -name "*.php" -o -name "*.txt" -o -name "*.xml" \)`
for FILE in ${FILES} 
do
	if [[ '' != ${NEW_TITLE} ]]
	then
		perl -pi -e "s#${OLD_TITLE}#${NEW_TITLE}#g" ${FILE}
	fi

	if [[ '' != ${NEW_SITE} ]]
	then
		perl -pi -e "s#${OLD_SITE}#${NEW_SITE}#g" ${FILE}
	fi

	if [[ '' != ${NEW_STORE_URL} ]]
	then
		perl -pi -e "s#${OLD_STORE_URL}#${NEW_STORE_URL}#g" ${FILE}
	fi

	if [[ '' != ${NEW_ABBR} ]]
	then
		perl -pi -e "s#${OLD_ABBR}#${NEW_ABBR}#g" ${FILE}
		perl -pi -e "s#${NEW_ABBR}_#${NEW_ABBR}#g" ${FILE}
	fi

	if [[ '' != ${NEW_BASE} ]]
	then
		perl -pi -e "s#${OLD_BASE}#${NEW_BASE}#g" ${FILE}
	fi

	if [[ '' != ${NEW_CLASS} ]]
	then
		perl -pi -e "s#${OLD_CLASS}#${NEW_CLASS}#g" ${FILE}
	fi

	if [[ '' != ${NEW_EXT} ]]
	then
		perl -pi -e "s#${OLD_EXT}#${NEW_EXT}#g" ${FILE}
		perl -pi -e "s#${OLD_EXT_SHORT}#${NEW_EXT_SHORT}#g" ${FILE}
	fi

	if [[ '' != ${NEW_EXTENDS} ]]
	then
		perl -pi -e "s#${OLD_EXTENDS}#${NEW_EXTENDS}#g" ${FILE}
	fi

	if [[ '' != ${NEW_EXTENDS_URL} ]]
	then
		perl -pi -e "s#${OLD_EXTENDS_URL}#${NEW_EXTENDS_URL}#g" ${FILE}
	fi

	if [[ '' != ${NEW_EXT_BASE} ]]
	then
		perl -pi -e "s#${OLD_EXT_BASE}#${NEW_EXT_BASE}#g" ${FILE}
	fi

	if [[ '' != ${NEW_EXT_API} ]]
	then
		perl -pi -e "s#${OLD_EXT_API}#${NEW_EXT_API}#g" ${FILE}
	fi

	if [[ '' != ${NEW_EXT_CHANGELOG} ]]
	then
		perl -pi -e "s#${OLD_EXT_CHANGELOG}#${NEW_EXT_CHANGELOG}#g" ${FILE}
	fi

	if [[ '' != ${NEW_EXT_TODO} ]]
	then
		perl -pi -e "s#${OLD_EXT_TODO}#${NEW_EXT_TODO}#g" ${FILE}
	fi

	if [[ '' != ${NEW_EXT_URL} ]]
	then
		perl -pi -e "s#${OLD_EXT_URL}#${NEW_EXT_URL}#g" ${FILE}
	fi

	if [[ '' != ${NEW_FILTER} ]]
	then
		perl -pi -e "s#${OLD_FILTER}#${NEW_FILTER}#g" ${FILE}
	fi

	if [[ '' != ${NEW_KB_PATH} ]]
	then
		perl -pi -e "s#${OLD_KB_PATH}#${NEW_KB_PATH}#g" ${FILE}
	fi

	if [[ '' != ${NEW_SLUG} ]]
	then
		perl -pi -e "s#${OLD_SLUG}#${NEW_SLUG}#g" ${FILE}
		perl -pi -e "s#${NEW_SLUG}_#${NEW_SLUG}#g" ${FILE}
	fi

	if [[ '' != ${NEW_SLUG_LONG} ]]
	then
		perl -pi -e "s#${OLD_SLUG_LONG}#${NEW_SLUG_LONG}#g" ${FILE}
	fi

	if [[ '' != ${NEW_NOTICE} ]]
	then
		perl -pi -e "s#${OLD_NOTICE}#${NEW_NOTICE}#g" ${FILE}
	fi

	if [[ '' != ${NEW_TAG} ]]
	then
		perl -pi -e "s#${OLD_TAG}#${NEW_TAG}#g" ${FILE}
	fi
done

FILE="README.md"
if [[ -e ${FILE} && '' != ${NEW_EXT} ]]
then
	NEW_THANK_YOU="Thank you \[${NEW_EXT}"
	OLD_THANK_YOU="Thank you \[${OLD_EXT}"
	perl -pi -e "s#${NEW_THANK_YOU}#${OLD_THANK_YOU}#g" ${FILE}

	NEW_REF_URL="jdevalk/${NEW_EXT_BASE}"
	OLD_REF_URL="jdevalk/${OLD_EXT_BASE}"
	perl -pi -e "s#${NEW_REF_URL}#${OLD_REF_URL}#g" ${FILE}
fi

if [[ -e 000-code-qa.txt ]]
then
	rm 000-code-qa.txt
fi

mv ${OLD_BASE}.php ${NEW_BASE}.php
mv languages/${OLD_BASE}.pot languages/${NEW_BASE}.pot
mv lib/class-${OLD_BASE}-licensing.php lib/class-${NEW_BASE}-licensing.php

if [[ -e .git ]]
then
	rm -rf .git
fi

if [[ -e "screenshot-1.png" ]]
then
	rm -rf screenshot-*.png
fi

vi *.md

git init
git add *
git add .gitignore
git add .travis.yml
git commit -m "Initial plugin creation"
git remote add origin git@github.com:michael-cannon/${NEW_BASE}.git