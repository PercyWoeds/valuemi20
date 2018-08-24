class AddChoferToNotes < ActiveRecord::Migration
  def change
    add_column :notes, :chofer, :string
  end
end
