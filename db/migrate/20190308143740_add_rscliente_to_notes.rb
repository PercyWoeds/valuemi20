class AddRsclienteToNotes < ActiveRecord::Migration
  def change
    add_column :notes, :rscliente, :string
  end
end
