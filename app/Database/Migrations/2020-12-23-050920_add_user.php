<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Class AddUser
 * @package App\Database\Migrations
 */
class AddUser extends Migration
{
    private $table_name = "user";
    private $fields = array(
        'user_id'       => [
            'type'           => 'INT',
            'constraint'     => 5,
            'unsigned'       => true,
            'auto_increment' => true,
        ],
        'first_name'    => [
            'type'           => 'VARCHAR',
            'constraint'     => '100',
        ],
        'last_name'     => [
            'type'           => 'VARCHAR',
            'constraint'     => '100',
            'null'           => true,
        ],
        'email'         => [
            'type'           => 'VARCHAR',
            'constraint'     => '100',
        ],
        'username'      => [
            'type'          => 'VARCHAR',
            'constraint'    => '100',
            'null'          => false,
        ],
        'phone' => [
            'type'           => 'VARCHAR',
            'constraint'     => '100',
            'null'           => true,
        ],
        'mobile'=> [
            'type'           => 'VARCHAR',
            'constraint'     => '100',
            'null'           => true,
        ],
        'password'      => [
            'type'           => 'VARCHAR',
            'constraint'     => '100',
            'null'           => true,
        ],
        'confirm_password'  => [
            'type'           => 'VARCHAR',
            'constraint'     => '100',
            'null'           => true,
        ],
        'address'           => [
            'type'           => 'TEXT',
            'null'           => true,
        ],
        'profile_image' => [
            'type'           => 'VARCHAR',
            'constraint'     => '100',
            'null'           => true,
        ],
        'profile_image_path' => [
            'type'           => 'VARCHAR',
            'constraint'     => '100',
            'null'           => true,
        ],
        'thumbnail_path' => [
            'type'           => 'VARCHAR',
            'constraint'     => '100',
            'null'           => true,
        ],
        'icon_path' => [
            'type'           => 'VARCHAR',
            'constraint'     => '100',
            'null'           => true,
        ],
        'login_oauth_uid' => [
            'type'           => 'VARCHAR',
            'constraint'     => '100',
            'null'           => true,
        ],
        'created_date_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_date_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    );

    public function up(){
        $this->forge->addField($this->fields);
        $this->forge->addKey('user_id', true);
        $this->forge->createTable($this->table_name);
    }

    public function down(){
        $this->forge->dropTable($this->table_name);
    }
}