<?php

namespace BD;

use Data\Config;
use Exception;



/*
* Classe amb les funcions generals per qualsevol BD
*/

/**
 * Paquet que agrupa totes les funcions que accedeixen a la base de dades
 * Conté les operacions simples i les genèriques
 */
class BD
{


    const CODI_EXCEP_RESTRIC = 2;

    const MES_ERROR_BD = "Error con la base de datos: ";

    /*Servei d'acces a la base de dades*/
    protected static $_instance = null;

    /**
     * @var \MysqliDb
     */
    protected $db;

    function __construct()
    {
        $config = json_decode(file_get_contents(PATH_DATABASE_CONFIG), true);
        $this->db = new \MysqliDb
        (
            $config['host'],
            $config['user'],
            $config['pass'],
            $config['db']
        );
        self::$_instance = $this;
    }

    /**
     * Get the last instance of this class
     * @return BD an instance of this class
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new BD();
        }
        return self::$_instance;
    }

    /**
     * Get the object used to acess to the database
     * @return \MysqliDb Object used to acces to the databse
     */
    public function getService()
    {
        return $this->db;
    }

    /**
     * Retorna un element de la base de dades(array)
     * @param    $taula -> nom de la taula de la base de dades que vols consultar
     * @param    string $where Concidicio del element a consultar
     * @param    string $cols Desired columns
     * @return  array amb la tupla que es vol consultar, nil si no ha anat bé
     * @throws  Exception when no register is found
     */
    function abd_getItemWhere($taula, $where, $cols = '*')
    {
        if ($where != '') {
            $this->db->where($where);
        }
        $row = $this->db->getOne($taula, $cols);
        if (!$row) {
            throw new Exception(self:: MES_ERROR_BD . "No se encontró el registro", 1);
        }
        return $row;
    }

    /**
     * @param string $taula
     * @param string $cols
     * @param string $colOrdenar nom de la columna per la que vols ordenar la taula
     * @param string $ordre 'ASC' or 'DESC'
     * @return array Query results, empty array if no results
     */
    function abd_consultaTaulaOrdenada($taula, $cols = '*', $colOrdenar, $ordre = 'ASC')
    {

        $this->db->orderBy($colOrdenar, $ordre);

        return $this->abd_consultaTaula($taula, $cols);
    }

    /**
     * Retorna un array amb els elements de la taula
     * @param string $taula nom de la taula de la base de dades que vols consultar
     * @param string $cols string amb les columnes a obtenir "col1, col2, ..., coln"
     * @param null|string $where sql where condition.
     * @return array
     */
    function abd_consultaTaula($taula, $cols = '*', $where = null)
    {
        if ($where != null) {
            $this->db->where($where);
        }
        $dades = $this->db->get($taula, null, $cols);
        if ($this->db->count == 0) {
            return [];
        }
        return $dades;
    }

    function abd_preparaConsulta($where = null, $colOrdenar = null, $ordre = null, $groupBy = null)
    {
        if ($where != null) {
            $this->db->where($where);
        }

        if ($colOrdenar != null && $ordre != null) {
            $this->db->orderBy($colOrdenar, $ordre);
        }

        if ($groupBy != null) {
            $this->db->groupBy($groupBy);
        }
    }

    /**
     * @param string $taula1 nom de la taula de la base de dades que vols consultar
     * @param string $taula2
     * @param string $onCondition string amb la condició ON del join (ie.: "id1=id2")
     * @param null|int $numRows number of rows to get, null to get all
     * @param null|string $cols string amb les columnes a mostrar "col1, col2, ..., coln"
     * @return array
     */
    function abd_consultaTaulaJOIN($taula1, $taula2, $onCondition, $numRows = null, $cols = null)
    {

        $this->db->join($taula1, $onCondition, "INNER");
        $result = $this->db->get($taula2, $numRows, $cols);

        return $result;
    }

    /**
     * Actualitza un element de una taula
     * @param    $taula -> nom de la taula de la base de dades que vols consultar
     *            $id        -> id del item a actualitzar
     *            $data       -> array associatiu [nomCamp => valorNou]
     * @return    true si ha anat bé
     * @throws
     */
    function abd_updateItem($taula, $id, $data)
    {
        if ($id == null) {
            throw new Exception('No ha llegado el identificador del registro', 1);
        }
        $this->db->where(\KEYS::PK[$taula], $id);
        if (!$this->db->update($taula, $data)) {
            throw new Exception(self:: MES_ERROR_BD . "No se pudo actualizar el registro: <br>" . $this->db->getLastError(), 1);
        }
        return true;
    }

    /**
     * Actualitza un element de una taula
     * @param    $taula -> nom de la taula de la base de dades que vols consultar
     *            $id        -> id del item a actualitzar
     *            $data       -> array associatiu [nomCamp => valorNou]
     * @throws Exception
     * @return    true si ha anat bé
     */
    function abd_updateWhere($taula, $where, $data)
    {
        $this->db->where($where);
        if (!$this->db->update($taula, $data)) {
            throw new Exception(self:: MES_ERROR_BD . "No se pudo actualizar el registro: <br>" . $this->db->getLastError(), 1);
        }
        return true;
    }

    /**
     * @param $taula String Nom de la taula de la base de dades
     * @param $id int id del item a consultar
     * @return bool true si ha anat bé
     * @throws Exception
     */
    function abd_deleteItem($taula, $id)
    {
        $this->db->where(\KEYS::PK[$taula], $id);
        if (!$this->db->delete($taula)) {
            throw new Exception(self::MES_ERROR_BD . "No se ha eliminado ningún registro:", self::CODI_EXCEP_RESTRIC);
        }
        return true;
    }

    public function abd_darreraConsulta()
    {
        return $this->db->getLastQuery();
    }

    public function abd_darrerError()
    {
        return $this->db->getLastError();
    }

    /**
     * Duplica el registre de la taula indicada i tota la informacio relacionada
     *
     * @param $taula
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function abd_duplicarInfo($taula, $id)
    {
        $item = $this->abd_getItem($taula, $id);
        return $this->abd_duplicarItem($taula, $item);
    }

    /**
     * Retorna un element de la base de dades(array)
     * @param    String $taula -> nom de la taula de la base de dades que vols consultar
     * @param    integer $id -> id primari del item a consultar
     * @param    string $cols Desired columns
     * @return array Retorna array amb la tupla que es vol consultar, nil si no ha anat bé
     * @throws Exception item not found
     */
    function abd_getItem($taula, $id, $cols = '*')
    {
        $this->db->where(\KEYS::PK[$taula], $id);
        $row = $this->db->getOne($taula, $cols);
        if (!$row) {
            throw new Exception(self:: MES_ERROR_BD . "No se encontró el registro");
        }
        return $row;
    }

    /**
     * @param $taula
     * @param $item
     * @return mixed
     * @throws Exception
     */
    private function abd_duplicarItem($taula, $item)
    { //nomes esta programat per una relacio per taula!
        if (isset($item['status'])) {
            unset($item['status']);///recuperar valor per defecte
        }

        // remove original id
        $id_original = $item['id'];
        unset($item['id']);

        $item['cdate'] = cdate();

        $id = $this->abd_insertItem($taula, $item);
        $item['id'] = $id;

        /* duplicam informacio relacionada */
        if (\KEYS::RELACIONS_TAULES[$taula] != null) {
            $relacions = \KEYS::RELACIONS_TAULES[$taula];
            //clau i contingut del primer item
            $taula_rel = key($relacions);
            $col_rel = current($relacions);

            $relacionats = $this->abd_consultaTaula($taula_rel, '*', $col_rel . '=' . $id_original);
            if ($relacionats) {
                foreach ($relacionats as $item_rel) {
                    $item_rel[$col_rel] = $id;
                    $this->abd_duplicarItem($taula_rel, $item_rel);
                }
            }
        }
        return $item;
    }

    /**
     * Add an item to database
     * @param $taula -> nom de la taula de la base de dades que vols consultar
     * @param $data -> informacio a inserir --> array associatiu [nomCamp => valorNou]
     * @return int id del nou element
     * @throws Exception
     */
    function abd_insertItem($taula, $data)
    {
        if (!$this->db->insert($taula, $data)) {
            throw new Exception(self::MES_ERROR_BD . "Error insertando dato:", 1);
        }
        return $this->db->getInsertId();
    }

    public function nextID($taula)
    {
        return $this->query("SELECT Max(id) as lastid FROM {$taula}")[0]['lastid'] + 1;
    }

    /**
     * Execute raw SQL query.
     *
     * @param string $sql User-provided query to execute.
     * @param array $params Variables array to bind to the SQL statement.
     * @throws Exception
     * @return array Contains the returned rows from the query.
     */
    public function query($sql, $params = null)
    {
        return $this->db->rawQuery($sql, $params);
    }

    // ------------------------------------------------------------------
    // * Backup de Base de datos
    public function backup()
    {
        $filename = 'dbbackup_' . date("dmY") . '.sql';
        $filess = __DIR__ . '/../../backup/' . $filename;

        $tables = array();
        $this->query("SET NAMES 'utf8'");
        $tables = $this->query('SHOW TABLES');
        //Missatges::debugVar($tables);
        // Cycle through each provided table
        // Generate the filename for the sql file

        $handle = fopen($filess, 'w+');
        foreach ($tables as $t) {
            $table = $t['Tables_in_' . config::DB['name']];

            $result = $this->query('SELECT * FROM ' . $table);

            // First part of the output – remove the table
            $return = 'DROP TABLE IF EXISTS ' . $table . ';';
            fwrite($handle, $return);
            // Second part of the output – create table
            $row2 = $this->query('SHOW CREATE TABLE ' . $table);
            $ssql_create = $row2[0]['Create Table'];
            //Missatges::debugVar($ssql_create);

            $return = "\n\n" . $ssql_create . ";\n\n";
            fwrite($handle, $return);

            //si no hi ha columnes botam a la seguent taula
            if (count($result) == 0) {
                break;
            }
            $num_fields = count($result[0]);

            // Third part of the output – insert values into new table
            foreach ($result as $row) {
                //Missatges::debugVar($row);
                $return = 'INSERT INTO ' . $table . ' VALUES(';
                fwrite($handle, $return);
                $j = 0;
                foreach ($row as $field) {
                    $return = '';
                    $field = addslashes($field);
                    $field = preg_replace("#\n#", "\\n", $field);
                    if (isset ($field)) {
                        $return .= '"' . $field . '"';
                    } else {
                        $return .= '""';
                    }
                    if ($j < ($num_fields - 1)) {
                        $return .= ',';
                    }
                    fwrite($handle, $return);
                    $j++;
                }
                fwrite($handle, ");\n");
            }
            fwrite($handle, $return);
            fwrite($handle, "\n\n\n");
        }

        // Save the sql file
        fclose($handle);

        // Print the message
        return $filename;
    }

    /**
     * @param $table
     * @param $colName
     * @return bool
     */
    public function hasColumn($table, $colName)
    {
        try {
            $res = $this->db->rawQuery("SHOW COLUMNS FROM " . $table . " LIKE '" . $colName . "'");
        }catch (Exception $e){
            return false;
        }
        return count($res) > 0;
    }
}