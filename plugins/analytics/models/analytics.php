<?php defined('SYSPATH') or die('No direct script access.');

class Analytics_Model extends Model {
	public function __construct()
	{
		parent::__construct(); 
	}

	public function get_incidents_grouped_by_id()
	{
		$prefix = $this->db->table_prefix();
		$sql = "SELECT COUNT( ".$prefix."category.id ) AS count, ".$prefix."category.category_title, ".$prefix."category.category_color 
				FROM ".$prefix."category 
				JOIN ".$prefix."incident_category ON ".$prefix."category.id=".$prefix."incident_category.category_id 
				JOIN ".$prefix."incident ON ".$prefix."incident_category.incident_id=".$prefix."incident.id 
				GROUP BY ".$prefix."category.id";
		return $this->db->query($sql);
	}

	public function get_categories( $category_id = -1 )
	{
		$prefix = $this->db->table_prefix();
		if( $category_id == -1 )
		{
			$sql = "SELECT ".$prefix."category.id, ".$prefix."category.category_title, ".$prefix."category.category_color 
					FROM ".$prefix."category";
		}
		else
		{
			$sql = "SELECT ".$prefix."category.id, ".$prefix."category.category_title, ".$prefix."category.category_color 
					FROM ".$prefix."category 
					WHERE ".$prefix."category.id=".$prefix . $category_id;
		}

		return $this->db->query($sql);
	}

	public function get_incidents_by_category( $category_id )
	{
		$prefix = $this->db->table_prefix();
		$sql = "SELECT COUNT( ".$prefix."category.id ) AS count, ".$prefix."category.id, ".$prefix."category.category_title, ".$prefix."category.category_color, ".$prefix."incident.incident_date 
				FROM ".$prefix."category 
				JOIN ".$prefix."incident_category ON ".$prefix."category.id=".$prefix."incident_category.category_id 
				JOIN ".$prefix."incident ON ".$prefix."incident_category.incident_id=".$prefix."incident.id 
				WHERE ".$prefix."category.id=".$category_id." 
				GROUP BY ".$prefix."incident.incident_date";
		return $this->db->query($sql);
	}

    public function get_incidents_table_D3_pc()
    {
	$p = $this->db->table_prefix();
	$sql = "
	    SELECT 
	    category_title AS Category, 
	    country AS Country,
	    incident_verified AS Verified, 
	    category_trusted AS 'Category Trusted',
	    incident_date AS 'Date (Unix)',
	    ".$p."incident.id AS 'Incident #'

	    FROM ".$p."incident INNER JOIN  ".$p."incident_category ON  incident.id = incident_category.incident_id
	    INNER JOIN ".$p."category ON incident_category.category_id
	    INNER JOIN ".$p."location ON incident.location_id = location.id
	    INNER JOIN ".$p."country ON location.country_id = country.id"
	;
	return $this->db->query( $sql );
    }
}
