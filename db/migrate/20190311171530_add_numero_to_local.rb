class AddNumeroToLocal < ActiveRecord::Migration
  def change
    add_column :locals, :numero, :string
  end
end
