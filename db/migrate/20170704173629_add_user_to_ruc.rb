class AddUserToRuc < ActiveRecord::Migration
  def change
    add_column :User, :ruc, :string
  end
end
