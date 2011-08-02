<?php
/* -*- mode: php; c-basic-offset: 4; indent-tabs-mode: nil; -*-
 * vim:expandtab:shiftwidth=4:tabstop=4:
 */

class UserManager
{
    private $db_request;

    public function __construct()
    {
        $this->db_request = new DatabaseRequest( 'app/config/database.xml' );
    }

   /**
    * This method returns Statistics object to create the pie graph or the user list
    * @return Statistics
    */
    public function getStat()
    {
        $count = array();
        $size = array();
        $blks = array();
        $db_result = $this->db_request->select( null, ACCT_TABLE, array(OWNER), null );

        $stat = new Statistics();

        foreach( $db_result as $line )
        {
            if ($line[OWNER] && $line['SUM('.COUNT.')'] && $line['SUM('.SIZE.')'] )
            {
                $count[$line[OWNER]] = $line['SUM('.COUNT.')'];
                $size[$line[OWNER]] = $line['SUM('.SIZE.')'];
                $blks[$line[OWNER]] = $line['SUM('.BLOCKS.')'];
            }
        }

        $stat->setSize( $size );
        $stat->setBlocks( $blks );
        $stat->setCount( $count );

        return $stat;
    }

   /**
    * This method returns detailed statistics for a specific user
    * @param user
    * @return db_result
    */
    public function getDetailedStat( $user, $sort )
    {
        $db_result = $this->db_request->select( array( OWNER => $user ), ACCT_TABLE, null, $sort );
        return $db_result;
    }

   /**
    * This method returns an associative array containing the schema of ACCT table
    * @return array
    */
    public function getAcctSchema()
    {
        return $this->db_request->getSchema( ACCT_TABLE );
    }

}
?>
