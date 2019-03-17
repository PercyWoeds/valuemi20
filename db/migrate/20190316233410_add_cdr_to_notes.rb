class AddCdrToNotes < ActiveRecord::Migration
  def change
    add_column :notes, :cdr, :string
  end
end
