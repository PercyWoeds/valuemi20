class AddImplistaToNotes < ActiveRecord::Migration
  def change
    add_column :notes, :implista, :float
    add_column :notes, :cod_tar, :string
    add_column :notes, :km, :string
    add_column :notes, :cod_sucu, :string
    add_column :notes, :isla, :string
    add_column :notes, :dni_cli, :string
    
  end
end
