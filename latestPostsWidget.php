<?php

class LatestPostsWidget extends Widget
{

    static $title = "Latest Blog Posts";
    static $cmsTitle = "Latest Blog Posts Widget";
    static $description = "This widget shows the latest blog posts";

    static $db = array(
        "WidgetTitle" => "Varchar(255)",
        "NumberToShow" => "Int",
        "BlogCategory" => "Varchar(255)",
        "ReadArticleLinkTitle" => "Varchar(255)",

    );

    static $defaults = array(
        "WidgetTitle" => "Latest Blog Posts",
        "NumberToShow" => 5,
        "ReadArticleLinkTitle" => "Read Article"
    );

    public function getCMSFields()
    {
        $categoryDropdown = new DropdownField(
            'BlogCategory',
            'Choose a Category',
            Dataobject::get("BlogCategory")->map('ID', 'Title')
        );
        return new FieldList(
            new TextField('WidgetTitle', 'Widget Title'),
            new NumericField("NumberToShow", "Number to Show"),
            $categoryDropdown->setEmptyString('All Categories'),
            new TextField('ReadArticleLinkTitle', 'Read Article Link Title')
        );
    }

    public function Title()
    {
        return $this->WidgetTitle ? $this->WidgetTitle : self::$title;
    }

    /**
     * Get blog posts and filter them by category
     * @return DataList
     */
    public function getLatestPosts()
    {
        $blogCat = $this->BlogCategory;
        $limit = $this->NumberToShow;
        $leftJoinOneTable = 'BlogEntry_BlogCategories';
        $leftJoinOne = '"BlogEntry_BlogCategories"."BlogEntryID" = "BlogEntry"."ID"';
        $leftJoinTwoTable = 'BlogCategory';
        $leftJoinTwo = '"BlogCategory"."ID" = "BlogEntry_BlogCategories"."BlogCategoryID"';

        if ($blogCat == null) {
            return DataList::create('BlogEntry')
                ->limit($limit)
                ->sort(array('Date' => 'DESC'))
                ->leftJoin($leftJoinOneTable, $leftJoinOne, null)
                ->leftJoin($leftJoinTwoTable, $leftJoinTwo, null);
        } else {
            return DataList::create('BlogEntry')
                ->limit($limit)
                ->sort(array('Date' => 'DESC'))
                ->leftJoin($leftJoinOneTable, $leftJoinOne, null)
                ->leftJoin($leftJoinTwoTable, $leftJoinTwo, null)
                ->where('BlogCategory.ID = ' . $blogCat);
        }
    }

    /**
     * Get blog post category
     * @return ArrayList
     */
    public function getPostCategory()
    {
        $blogCat = $this->BlogCategory;
        $set = new ArrayList;
        $leftJoinTable = 'BlogEntry_BlogCategories';
        $leftJoin = '"BlogEntry_BlogCategories"."BlogCategoryID" = "BlogCategory"."ID"';

        if ($blogCat == null) {
            return false;
        } else {
            foreach(BlogCategory::get()
                ->leftJoin($leftJoinTable, $leftJoin, null)
                ->where('BlogCategoryID = ' . $blogCat) 
                as $obj)
                $set->push($obj);
            return $set;
        }
    }
}
