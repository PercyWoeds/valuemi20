class AddCdrExtensionToNotes < ActiveRecord::Migration
  def change
    add_column :notes, :cdr_extension, :string
  end
end
