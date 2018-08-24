class AddProcesadoToNotes < ActiveRecord::Migration
  def change
    add_column :notes, :procesado, :string
  end
end
